<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lead extends Model
{
    protected $table = 'leads';

    protected $fillable = [
        'facebook_lead_id',
        'form_id',
        'form_name',
        'ad_id',
        'campaign_id',
        'page_id',
        'field_data',
        'extracted_fields',
        'name',
        'email',
        'phone',
        'status',
        'sync_status',
        'sync_error',
        'sync_attempts',  // ✅ Added
        'created_time',
        'synced_at',
    ];

    protected $casts = [
        'field_data' => 'array',
        'extracted_fields' => 'array',
        'created_time' => 'datetime',
        'synced_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sync_attempts' => 'integer',  // ✅ Added
    ];

    /**
     * Scope to get pending leads
     */
    public function scopePending($query)
    {
        return $query->where('sync_status', 'pending');
    }

    /**
     * Scope to get failed leads
     */
    public function scopeFailed($query)
    {
        return $query->where('sync_status', 'failed');
    }

    /**
     * Scope to get synced leads
     */
    public function scopeSynced($query)
    {
        return $query->where('sync_status', 'synced');
    }

    /**
     * Scope to get recent leads
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', Carbon::now()->subHours($hours));
    }

    /**
     * Scope to get leads that need retry (failed but not exceeded max attempts)
     */
    public function scopeNeedsRetry($query)
    {
        return $query->where('sync_status', 'failed')
            ->where(function($q) {
                $q->whereNull('sync_attempts')
                  ->orWhere('sync_attempts', '<', 3);
            });
    }

    /**
     * Check if lead is synced
     */
    public function isSynced()
    {
        return $this->sync_status === 'synced';
    }

    /**
     * Check if lead is failed
     */
    public function isFailed()
    {
        return $this->sync_status === 'failed';
    }

    /**
     * Check if lead needs retry
     */
    public function needsRetry()
    {
        return $this->isFailed() && ($this->sync_attempts ?? 0) < 3;
    }

    /**
     * Check if lead has exceeded max retry attempts
     */
    public function hasExceededRetries()
    {
        return ($this->sync_attempts ?? 0) >= 3;
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) {
            return null;
        }
        
        return preg_replace('/[^0-9+]/', '', $this->phone);
    }

    /**
     * Get field value from field_data by name
     */
    public function getFieldValue($fieldName)
    {
        if (!is_array($this->field_data)) {
            return null;
        }

        foreach ($this->field_data as $field) {
            if (isset($field['name']) && strtolower($field['name']) === strtolower($fieldName)) {
                return $field['values'][0] ?? null;
            }
        }

        return null;
    }

    /**
     * Mark lead as synced
     */
    public function markAsSynced()
    {
        $this->update([
            'sync_status' => 'synced',
            'synced_at' => now(),
            'sync_error' => null
        ]);
    }

    /**
     * Mark lead as failed
     */
    public function markAsFailed($errorMessage)
    {
        $this->update([
            'sync_status' => 'failed',
            'sync_error' => substr($errorMessage, 0, 500)
        ]);
    }
}
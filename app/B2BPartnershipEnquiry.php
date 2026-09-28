<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Enquiries submitted from the website's /b2b-partnerships form (saved by dookwebsite).
class B2BPartnershipEnquiry extends Model
{
    // Timestamps are stored in UTC; the admin team reads them in IST.
    const DISPLAY_TIMEZONE = 'Asia/Kolkata';

    // Options offered by the website form.
    const DESTINATIONS = ['Uzbekistan', 'Kazakhstan', 'Kyrgyzstan', 'Georgia', 'Armenia', 'Azerbaijan', 'Tajikistan', 'Turkmenistan', 'Please advise'];
    const TRAVEL_TYPES = ['FIT / tailor-made', 'Group / series', 'MICE / incentive', 'Luxury travel', 'Multi-country', 'Partnership enquiry'];

    protected $table = 'b2b_partnership_enquiries';

    protected $casts = [
        'destinations' => 'array',
        'travel_month' => 'date',
        'contact_consent_at' => 'datetime',
    ];

    public function receivedAt()
    {
        return $this->created_at->copy()->timezone(self::DISPLAY_TIMEZONE);
    }

    public static function destinationLabel($destination)
    {
        return $destination === 'Please advise' ? 'Help me choose' : $destination;
    }

    // Digits only, for wa.me links.
    public function whatsappNumber()
    {
        return preg_replace('/\D/', '', $this->mobile);
    }
}

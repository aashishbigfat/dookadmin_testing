<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    public function ducuments()
     {
     	return $this->belongsToMany('App\Document','visa_documents')->withTimestamps();
     }
}

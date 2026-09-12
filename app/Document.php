<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function visas()
     {
     	return $this->belongsToMany('App\Visa','visa_documents')->withTimestamps();
     }

}

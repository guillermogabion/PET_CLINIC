<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'name',
        'start',
        'end',
        'contact',
        'description',
    ];

  
}

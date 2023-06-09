<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //

    protected $fillable = [
        'client_name',
        'contact',
        'address',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    
}

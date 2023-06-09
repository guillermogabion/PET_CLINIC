<?php

namespace App;
use App\Record;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    //

    protected $fillable = [
        'id',
        'pet_id',
        'service',
        'brand_name',
        'datevisitss',
        'duration',
        'remarks'
    ];
}

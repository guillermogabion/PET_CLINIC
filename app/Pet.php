<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = ['id','patient_id', 'pet_name', 'specie', 'sex', 'age', 'breed', 'color'];

    public function owner(){
        return $this->belongsTo(Patient::class, 'id');
    }

    public function record(){
        return $this->hasMany(Record::class, 'pet_id', 'id');
    }

}
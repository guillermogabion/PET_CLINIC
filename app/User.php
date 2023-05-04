<?php

namespace App;
use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mid_name', 'suffix', 'stud_no', 'programed', 'dobirthd', 'brgy', 'town', 'province', 'parent_first', 'parent_last', 'parent_mid', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function record(){
    //     return $this->hasMany(Record::class, 'student_id', 'id'); 
    // }

    public function record(){
        return $this->hasMany(Record::class, 'student_id', 'id')->orderBy('created_at', 'desc');
    }
    
}

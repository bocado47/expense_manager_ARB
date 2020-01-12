<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class UserData extends Model
{
    protected $table='users';
    protected $fillable=['name','password','email','role'];

}

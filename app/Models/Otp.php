<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['identifier','identifier_type','code','expires_at','isVerified'];
    protected $dates = ['expires_at'];
}

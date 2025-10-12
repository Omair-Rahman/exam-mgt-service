<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CountdownSetting extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'target_at'];

    protected $casts = [
        'target_at' => 'datetime',
    ];
}
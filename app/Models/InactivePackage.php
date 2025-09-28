<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InactivePackage extends Model
{
    protected $fillable = [
        'exam_time_minutes','question_number','pass_mark_percent',
        'exam_instructions','starts_at','ends_at','archived_at',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'ends_at'     => 'datetime',
        'archived_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'question_limit',
        'is_active',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'exam_time_minutes','question_number','pass_mark_percent',
        'exam_instructions','starts_at','ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    // categories table acts as “subjects”
    public function subjects()
    {
        return $this->belongsToMany(Category::class, 'package_subject', 'package_id', 'subject_id');
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        if ($now->lt($this->starts_at)) return 'Upcoming';
        if ($now->between($this->starts_at, $this->ends_at)) return 'Live';
        return 'Finished';
    }
};

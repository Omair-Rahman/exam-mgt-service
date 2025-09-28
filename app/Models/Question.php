<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id','subcategory_id','question_year_id',
        'question','answer_1','answer_2','answer_3','answer_4',
        'correct_option','explanation','question_hash','is_active',
    ];

    public function category()     { return $this->belongsTo(Category::class); }
    public function subcategory()  { return $this->belongsTo(Subcategory::class); }
    public function questionYear() { return $this->belongsTo(QuestionYear::class); }
}

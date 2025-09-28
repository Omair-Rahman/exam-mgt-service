<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','question_limit','is_active'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    protected $casts = [
        'question_limit' => 'integer',
        'is_active'      => 'boolean',
    ];
}

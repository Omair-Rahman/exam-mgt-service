<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'exam_time_minutes' => 'required|integer|min:20|max:120',
            'question_number'   => 'required|integer|min:25|max:200',
            'pass_mark_percent' => 'required|numeric|min:40|max:100',
            'exam_instructions' => 'nullable|string',
            'starts_at'         => 'required|date',
            'ends_at'           => 'required|date|after:starts_at',
            'subjects'          => 'required|array|min:1',
            'subjects.*'        => 'exists:categories,id',
        ];
    }
}
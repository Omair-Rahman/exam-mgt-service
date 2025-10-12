<?php

namespace App\Http\Controllers;

use App\Models\QuestionYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class QuestionYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list')->only('index');
        $this->middleware('permission:create')->only(['create', 'store']);
        $this->middleware('permission:update')->only(['edit', 'update']);
        $this->middleware('permission:delete')->only('destroy');
    }

    public function index()
    {
        $years = QuestionYear::orderBy('year','desc')->get();
        return view('backend.question_years.index', compact('years'));
    }

    public function create()
    {
        return view('backend.question_years.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year'           => ['required','string','max:10','unique:question_years,year'],
            'question_limit' => ['required','integer','min:1','max:200'], // field must be ≤ 200
            'is_active'      => ['nullable','boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        QuestionYear::create($data);

        Alert::toast('Question Year created successfully!', 'success')->position('top-end');
        return redirect()->route('question_years.index');
    }

    public function edit(QuestionYear $question_year)
    {
        return view('backend.question_years.edit', ['questionYear' => $question_year]);
    }

    public function update(Request $request, QuestionYear $question_year)
    {
        $data = $request->validate([
            'year'           => ['required','string','max:10', Rule::unique('question_years','year')->ignore($question_year->id)],
            'question_limit' => ['required','integer','min:1','max:200'],
            'is_active'      => ['nullable','boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $question_year->update($data);

        Alert::toast('Question Year updated successfully!', 'success')->position('top-end');
        return redirect()->route('question_years.index');
    }

    public function destroy(QuestionYear $question_year)
    {
        $question_year->delete();
        
        Alert::toast('Question Year deleted successfully!', 'success')->position('top-end');
        return redirect()->route('question_years.index');
    }
};
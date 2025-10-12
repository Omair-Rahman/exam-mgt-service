<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\QuestionYear;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class QuestionController extends Controller
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
        $questions = Question::with(['category','subcategory','questionYear'])
            ->latest('id')->paginate(20);

        return view('backend.questions.index', compact('questions'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['id','name','question_limit']);
        $years      = QuestionYear::orderByDesc('year')->get(['id','year','question_limit']);
        return view('backend.questions.create', compact('categories','years'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'category_id'      => ['required','exists:categories,id'],
            'subcategory_id'   => ['nullable','exists:subcategories,id'],
            'question_year_id' => ['required','exists:question_years,id'],

            'question'         => ['required','string'],
            'answer_1'         => ['required','string'],
            'answer_2'         => ['required','string'],
            'answer_3'         => ['required','string'],
            'answer_4'         => ['required','string'],
            'correct_option'   => ['required','integer','in:1,2,3,4'],
            'explanation'      => ['nullable','string'],

            'is_active'        => ['nullable','boolean'],
        ]);
        $data['is_active'] = $r->boolean('is_active');

        // Subcategory must belong to selected category
        if (!empty($data['subcategory_id'])) {
            $sub = Subcategory::find($data['subcategory_id']);
            if (!$sub || (int)$sub->category_id !== (int)$data['category_id']) {
                return back()->withErrors(['subcategory_id' => 'Selected subcategory does not belong to the chosen category.'])
                             ->withInput();
            }
        }

        // Unique question by content hash
        $data['question_hash'] = hash('sha256', $data['question']);
        if (Question::where('question_hash', $data['question_hash'])->exists()) {
            return back()->withErrors(['question' => 'This exact question already exists (unique by content).'])
                         ->withInput();
        }

        // Enforce caps (year total, and per-year-per-category)
        try {
            DB::transaction(function () use ($data) {
                $category = Category::whereKey($data['category_id'])->lockForUpdate()->firstOrFail();
                $year     = QuestionYear::whereKey($data['question_year_id'])->lockForUpdate()->firstOrFail();

                $yearLimit = $year->question_limit ?? 200;
                $catLimit  = $category->question_limit ?? 200;

                $usedYear = Question::where('question_year_id', $year->id)->count();
                if ($usedYear + 1 > $yearLimit) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'question_year_id' => "Year limit exceeded ({$usedYear}/{$yearLimit}).",
                    ]);
                }

                $usedCatInYear = Question::where('question_year_id', $year->id)
                                         ->where('category_id', $category->id)
                                         ->count();
                if ($usedCatInYear + 1 > $catLimit) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'category_id' => "Category limit for this year exceeded ({$usedCatInYear}/{$catLimit}).",
                    ]);
                }

                Question::create($data);
            });
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return back()->withErrors($ve->errors())->withInput();
        }

        Alert::toast('Question created successfully!', 'success')->position('top-end');
        return redirect()->route('questions.index');
    }

    public function edit(Question $question)
    {
        $categories = Category::orderBy('name')->get(['id','name','question_limit']);
        $years      = QuestionYear::orderByDesc('year')->get(['id','year','question_limit']);
        $subcats    = Subcategory::where('category_id', $question->category_id)->orderBy('name')->get(['id','name','category_id']);

        return view('backend.questions.edit', compact('question','categories','years','subcats'));
    }

    public function update(Request $r, Question $question)
    {
        $data = $r->validate([
            'category_id'      => ['required','exists:categories,id'],
            'subcategory_id'   => ['nullable','exists:subcategories,id'],
            'question_year_id' => ['required','exists:question_years,id'],

            'question'         => ['required','string'],
            'answer_1'         => ['required','string'],
            'answer_2'         => ['required','string'],
            'answer_3'         => ['required','string'],
            'answer_4'         => ['required','string'],
            'correct_option'   => ['required','integer','in:1,2,3,4'],
            'explanation'      => ['nullable','string'],

            'is_active'        => ['nullable','boolean'],
        ]);
        $data['is_active'] = $r->boolean('is_active');

        if (!empty($data['subcategory_id'])) {
            $sub = Subcategory::find($data['subcategory_id']);
            if (!$sub || (int)$sub->category_id !== (int)$data['category_id']) {
                return back()->withErrors(['subcategory_id' => 'Selected subcategory does not belong to the chosen category.'])
                             ->withInput();
            }
        }

        // Unique by content hash (ignore current id)
        $data['question_hash'] = hash('sha256', $data['question']);
        $dup = Question::where('question_hash', $data['question_hash'])
                       ->where('id','!=',$question->id)
                       ->exists();
        if ($dup) {
            return back()->withErrors(['question' => 'Another question with the same content already exists.'])
                         ->withInput();
        }

        // If moving to another year/category, re-check caps
        try {
            DB::transaction(function () use ($data, $question) {
                $category = Category::whereKey($data['category_id'])->lockForUpdate()->firstOrFail();
                $year     = QuestionYear::whereKey($data['question_year_id'])->lockForUpdate()->firstOrFail();

                $yearLimit = $year->question_limit ?? 200;
                $catLimit  = $category->question_limit ?? 200;

                $usedYear = Question::where('question_year_id', $year->id)
                                    ->where('id','!=',$question->id)->count();
                if ($usedYear + 1 > $yearLimit) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'question_year_id' => "Year limit exceeded ({$usedYear}/{$yearLimit}).",
                    ]);
                }

                $usedCatInYear = Question::where('question_year_id', $year->id)
                                         ->where('category_id', $category->id)
                                         ->where('id','!=',$question->id)
                                         ->count();
                if ($usedCatInYear + 1 > $catLimit) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'category_id' => "Category limit for this year exceeded ({$usedCatInYear}/{$catLimit}).",
                    ]);
                }

                $question->update($data);
            });
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return back()->withErrors($ve->errors())->withInput();
        }

        Alert::toast('Question updated successfully!', 'success')->position('top-end');
        return redirect()->route('questions.index');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        Alert::toast('Question deleted successfully!', 'success')->position('top-end');
        return back();
    }

    // AJAX — subcategories for a category
    public function subcategories(Request $r)
    {
        $r->validate(['category_id' => ['required','exists:categories,id']]);
        return Subcategory::where('category_id', $r->category_id)->orderBy('name')->get(['id','name','category_id']);
    }

    // AJAX — remaining counters
    public function remaining(Request $r)
    {
        $r->validate([
            'year_id'     => ['required','exists:question_years,id'],
            'category_id' => ['nullable','exists:categories,id'],
        ]);

        $year = QuestionYear::findOrFail($r->year_id);
        $yearUsed = Question::where('question_year_id', $year->id)->count();
        $yearRemaining = max(0, ($year->question_limit ?? 200) - $yearUsed);

        $categoryRemaining = null;
        if ($r->filled('category_id')) {
            $cat = Category::findOrFail($r->category_id);
            $catUsedInYear = Question::where('question_year_id', $year->id)
                                     ->where('category_id', $cat->id)->count();
            $categoryRemaining = max(0, ($cat->question_limit ?? 200) - $catUsedInYear);
        }

        return response()->json([
            'year_remaining'     => $yearRemaining,
            'category_remaining' => $categoryRemaining,
        ]);
    }
    // ----------------- NEW: Full table (all years) -----------------
    public function tableAll()
    {
        $years = QuestionYear::orderByDesc('year')->get(['id','year']);
        $questions = Question::with([
            'questionYear:id,year',
            'category:id,name',
            'subcategory:id,name'
        ])->latest('id')->get();

        return view('backend.questions.table_all', compact('years','questions'));
    }

    // ----------------- NEW: Table for a chosen year -----------------
    public function tableByYear(QuestionYear $year)
    {
        $years = QuestionYear::orderByDesc('year')->get(['id','year']);
        $questions = Question::with([
            'questionYear:id,year',
            'category:id,name',
            'subcategory:id,name'
        ])->where('question_year_id', $year->id)
        ->latest('id')->get();

        return view('backend.questions.table_year', compact('years','year','questions'));
    }

    // ----------------- NEW (optional): AJAX partial for a year ------
    public function ajaxByYear(QuestionYear $year)
    {
        $questions = Question::with([
            'questionYear:id,year',
            'category:id,name',
            'subcategory:id,name'
        ])->where('question_year_id', $year->id)
        ->latest('id')->get();

        // return HTML partial so you can replace the <tbody> without reload
        return view('backend.questions._table_body', compact('questions'));
    }

}

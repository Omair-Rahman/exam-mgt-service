<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:list')->only('index');
        $this->middleware('permission:create')->only(['create', 'store']);
        $this->middleware('permission:update')->only(['edit', 'update']);
        $this->middleware('permission:delete')->only('destroy');
    }
    
    public function index()
    {
        $categories = Category::all();
        return view('backend.category.index', compact('categories'));
    }
    public function create()
    {
        return view('backend.category.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'           => ['required', 'string', 'max:150', 'unique:categories,name'],
            'question_limit' => ['required', 'integer', 'min:10', 'max:35'],
            'is_active'      => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $r->boolean('is_active');
        Category::create($data);
        Alert::toast('Category created successfully.', 'success')->position('top-end');
        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return view('backend.category.edit', compact('category'));
    }

    public function update(Request $r, Category $category)
    {
        $data = $r->validate([
            'name'           => ['required', 'string', 'max:150', Rule::unique('categories', 'name')->ignore($category->id)],
            'question_limit' => ['required', 'integer', 'min:10', 'max:35'],
            'is_active'      => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $category->update($data);
        Alert::toast('Category updated successfully.', 'success')->position('top-end');
        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        Alert::toast('Category deleted successfully.', 'success')->position('top-end');
        return redirect()->route('categories.index');
    }
}

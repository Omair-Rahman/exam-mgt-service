<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
class SubcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:list')->only('index');
        $this->middleware('permission:create')->only(['create', 'store']);
        $this->middleware('permission:update')->only(['edit', 'update']);
        $this->middleware('permission:delete')->only('destroy');
    }
    
    public function index()  { 
        $categories = Category::with('subcategories')->get();
        return view('backend.subcategory.index', compact('categories')); 
    }
    public function create() { 
        $categories = Category::orderBy('name')->get(); 
        return view('backend.subcategory.create', compact('categories')); 
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:150'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['is_active'] = $r->boolean('is_active');
        Subcategory::create($data);
        Alert::toast('Subcategory created successfully.', 'success')->position('top-end');
        return redirect()->route('subcategories.index');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        return view('backend.subcategory.edit', compact('subcategory','categories'));
    }

    public function update(Request $r, Subcategory $subcategory)
    {
        $data = $r->validate([
            'category_id' => ['required','exists:categories,id'],
            'name' => ['required','string','max:150'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['is_active'] = $r->boolean('is_active');
        $subcategory->update($data);
        Alert::toast('Subcategory updated successfully.', 'success')->position('top-end');
        return redirect()->route('subcategories.index');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        Alert::toast('Subcategory deleted successfully.', 'success')->position('top-end');
        return redirect()->route('subcategories.index');
    }

    // Dependent dropdown endpoint
    public function byCategory(Request $r)
    {
        $r->validate(['category_id' => ['required','exists:categories,id']]);
        return Subcategory::where('category_id', $r->category_id)
            ->orderBy('name')
            ->get(['id','name']);
    }
}
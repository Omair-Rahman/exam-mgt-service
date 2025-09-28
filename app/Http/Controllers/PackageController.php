<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
use App\Models\Category;
use App\Models\InactivePackage;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('subjects:id,name')->latest()->get();
        return view('backend.package.index', compact('packages'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['id','name']);
        return view('backend.package.create', compact('categories'));
    }

    public function store(PackageRequest $request)
    {
        DB::transaction(function () use ($request) {
            $package = Package::create($request->only([
                'name',
                'exam_time_minutes','question_number','pass_mark_percent',
                'exam_instructions','starts_at','ends_at',
            ]));
            $package->subjects()->sync($request->input('subjects', []));
        });

        Alert::toast('Package created successfully.','success')->position('top-end');
        return redirect()->route('packages.index');
    }

    public function edit(Package $package)
    {
        $categories = Category::orderBy('name')->get(['id','name']);
        $package->load('subjects');
        return view('backend.package.edit', compact('package','categories'));
    }

    public function update(PackageRequest $request, Package $package)
    {
        DB::transaction(function () use ($request, $package) {
            $package->update($request->only([
                'name',
                'exam_time_minutes','question_number','pass_mark_percent',
                'exam_instructions','starts_at','ends_at',
            ]));
            $package->subjects()->sync($request->input('subjects', []));
        });

        Alert::toast('Package updated successfully.','success')->position('top-end');
        return redirect()->route('packages.index');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        Alert::toast('Package deleted successfully.','success')->position('top-end');
        return redirect()->route('packages.index');
    }
};
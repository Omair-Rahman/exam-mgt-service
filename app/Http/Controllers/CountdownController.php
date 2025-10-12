<?php

namespace App\Http\Controllers;

use App\Models\CountdownSetting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CountdownController extends Controller
{
    // If you want to restrict to admins:
    // public function __construct()
    // {
    //     $this->middleware(['auth','role:super_admin,admin']);
    // }

    // Single edit screen (create if not exists)
    public function edit()
    {
        $setting = CountdownSetting::first();
        return view('backend.countdown.edit', compact('setting'));
    }

    // Store or update the single record
    public function save(Request $request)
    {
        $data = $request->validate([
            'title'     => ['required','string','max:150'],
            'target_at' => ['required','date'],
        ]);

        $setting = CountdownSetting::first();
        if ($setting) {
            $setting->update($data);
            Alert::toast('Countdown updated successfully!', 'success')->position('top-end');
        } else {
            CountdownSetting::create($data);
            Alert::toast('Countdown created successfully!', 'success')->position('top-end');
        }

        return redirect()->route('countdown.edit');
    }

    // Public endpoint that returns current countdown data (JSON) - optional
    public function json()
    {
        $setting = CountdownSetting::first();
        if (!$setting) {
            return response()->json(['exists' => false], 200);
        }

        return response()->json([
            'exists' => true,
            'title'  => $setting->title,
            'target' => $setting->target_at?->toIso8601String(),
            'server_now' => now()->toIso8601String(),
        ]);
    }
}

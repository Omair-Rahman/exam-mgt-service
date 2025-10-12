<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function admin(){
        abort_unless(!in_array(Auth::user()->role, ['examinee']), 403, 'Not allowed');

        return view('dashboard');
    }

    public function manager()
    {
        abort_unless(!in_array(Auth::user()->role, ['examinee']), 403, 'Not allowed');

        return view('backend.dashboard.manager');
    }

    public function employee()
    {
        abort_unless(!in_array(Auth::user()->role, ['examinee']), 403, 'Not allowed');

        return view('backend.dashboard.employee');
    }

    public function advUser()
    {
        abort_unless(!in_array(Auth::user()->role, ['examinee']), 403, 'Not allowed');

        return view('backend.dashboard.adv-user');
    }
}

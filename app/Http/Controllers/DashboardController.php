<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function manager()
    {
        return view('backend.dashboard.manager');
    }

    public function employee()
    {
        return view('backend.dashboard.employee');
    }

    public function advUser()
    {
        return view('backend.dashboard.adv-user');
    }
}

<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $page_heading = "Vendor Dashboard";
        return view('vendor.dashboard', compact('page_heading'));
    }
}

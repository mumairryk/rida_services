<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    function serviceRequest(){
        return view('admin.service_providers.service_request');
    }
}

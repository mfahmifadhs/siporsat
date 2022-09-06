<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperUserController extends Controller
{
    public function index()
    {
        return view('v_super_user.index');
    }

    public function dashboardOldat()
    {
        return view('v_super_user.apk_oldat.index');
    }
}

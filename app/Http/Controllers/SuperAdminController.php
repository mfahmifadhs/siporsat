<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Models\User;

use DB;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SuperadminController extends Controller
{
    public function index()
    {
        return view('v_super_admin.index');
    }

    // ====================================================
    //                      LEVEL
    // ====================================================

    public function showLevel(Request $request, $aksi, $id)
    {

    }

}

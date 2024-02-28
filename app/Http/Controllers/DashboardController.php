<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Tamu;
use App\Models\Trayek;
use App\Models\UnitKerja;
use Hash;
use Auth;
use Carbon\Carbon;
use Session;
use DB;

class DashboardController extends Controller
{

    public function index()
    {
        $role = Auth::user()->level_id;
        if ($role == 4) {
            return view('dashboard.user');
        } else {
            return view('dashboard.admin');
        }
    }
}

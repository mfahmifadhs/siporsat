<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Session;
use DB;

class AuthController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function postMasuk(Request $request)
    {
        $request->validate([
            'username'  => 'required',
            'password'  => 'required',
        ]);
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')->with('success','Berhasil Masuk !');
        }
        return redirect("masuk")->with('failed', 'Username atau Password Salah !');
    }



    public function registration()
    {
        return view('registration');
    }

    public function postRegistration(Request $request)
    {
        $request->validate([
            'id'        => 'required',
            'full_name' => 'required',
            'username'  => 'required',
            'password'  => 'required|min:6',
        ]);
        $data = $request->all();
        $check = $this->create($data);
        return redirect("dashboard")->with('Success', 'Berhasil Login !');
    }


    public function create(array $data)
    {
        return User::create([
            'id'        => $data['id'],
            'role_id'   => '3',
            'full_name' => $data['full_name'],
            'nip'       => $data['nip'],
            'password'  => Hash::make($data['password']),
            'status_id' => '1',
        ]);
    }


    public function dashboard()
    {
        if(Auth::check() && Auth::user()->level_id == 1)
        {
            return redirect('super-admin/dashboard');
        }
        elseif (Auth::check() && Auth::user()->level_id == 2)
        {
            return redirect('super-user/dashboard');
        }
        elseif (Auth::check() && Auth::user()->level_id == 3)
        {
            return redirect('user/dashboard');
        }
    }


    public function keluar() {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}

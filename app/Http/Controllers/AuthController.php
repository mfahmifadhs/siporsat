<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Hash;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function redirect()
    {
        $ssoBaseUrl  = "https://auth-eoffice.kemkes.go.id/";
        $clientId    = app('config')->get('services.sso.client_id');
        $redirectUri = "https://siporsat.kemkes.go.id/auth/sso/callback";

        $redirectUrl = $ssoBaseUrl . "/oauth/authorize" . "?client_id=" . $clientId . "&redirect_uri=" . urlencode($redirectUri) . "&response_type=code";

        return Redirect::away($redirectUrl);
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');

        $response = Http::asForm()->post(config('services.sso.base_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => config('services.sso.redirect'),
            'code' => $code,
        ]);

        $tokenData = $response->json();

        if (!isset($tokenData['access_token'])) {
            return redirect('/')->withErrors('Login failed. No response from eOffice.');
        }

        $userResponse = Http::withToken($tokenData['access_token'])->post(config('services.sso.base_url') . '/oauth/usertoken');
        $userData = $userResponse->json();

        if (empty($userData['nip'])) {
            return redirect('/')->withErrors('Login failed. NIP not found.');
        }

        $user = User::join('t_pegawai', 'id_pegawai', 'pegawai_id')
            ->where('nip_pegawai', $userData['nip'])
            ->first();

        if (!$user) {
            return redirect()->route('masuk')->with('failed', 'Pengguna tidak terdaftar');
        }

        Auth::login($user);
        return redirect()->intended('dashboard');
    }

    public function postMasuk(Request $request, $id)
    {
        if (Crypt::decrypt($id) == 'masuk.post') {
            $request->validate([
                'username'  => 'required',
                'password'  => 'required',
            ]);

            if ( $request->captcha == null) {
                return back()->with('failed','mohon isi kode captcha');
            } elseif(captcha_check($request->captcha) == false ) {
                return back()->with('failed','captcha salah');
            } else {
                    $credentials = $request->only('username', 'password');
                    if (Auth::attempt($credentials)) {
                        return redirect()->intended('dashboard')->with('success','Berhasil Masuk !');
                    }
                    return redirect("masuk")->with('failed', 'Username atau Password Salah !');
            }
        } else {
            return back()->with('failed','Anda Tidak Memiliki Akses !');
        }
    }

    public function reloadCaptcha() {
        return response()->json(['captcha' => captcha_img()]);
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
            return redirect('admin-user/dashboard');
        }
        elseif (Auth::check() && Auth::user()->level_id == 3)
        {
            return redirect('super-user/dashboard');
        }
        elseif (Auth::check() && Auth::user()->level_id == 4)
        {
            return redirect('unit-kerja/dashboard');
        }
    }


    public function keluar() {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}

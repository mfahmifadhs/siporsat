<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class CekUker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if(Auth::user()->pegawai->unitKerja->unit_utama_id != '02401'){
            return redirect('/unit-kerja/dashboard')->with('failed','Anda tidak memiliki akses!');
        }

        return $next($request);


    }
}

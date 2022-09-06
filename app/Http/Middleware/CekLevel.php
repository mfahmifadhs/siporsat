<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class CekLevel
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
        if(Auth::user() == null){
            return redirect('/')->with('failed','Anda tidak memiliki akses!');
        }elseif ($role == 'super-admin' && auth()->user()->level_id != 1) {
            abort(403);
        }elseif ($role == 'admin-user' && auth()->user()->level_id != 2) {
            abort(403);
        }elseif ($role == 'super-user' && auth()->user()->level_id != 3) {
            abort(403);
        }elseif ($role == 'user' && auth()->user()->level_id != 4) {
            abort(403);
        }

        return $next($request);


    }
}

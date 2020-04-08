<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ChekIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user->isAdmin()){
            session()->flash('warning', 'У вас нет права администратора');
            return redirect()->route('index');
        };
        return $next($request);
    }
}

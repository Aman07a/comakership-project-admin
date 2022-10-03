<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActiveUser
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->blocked_at) {
            $user = auth()->user();
            auth()->logout();
            return redirect()->route('login')
                ->withError('Your account was blocked at ' . Carbon::parse($user->blocked_at)->format('Y-m-d H:i:s'));
        }

        // if (auth()->user()->deleted_at) {
        //     $user = auth()->user();
        //     auth()->logout();
        //     return redirect()->route('login')
        //         ->withError('Your account was deleted at ' . Carbon::parse($user->deleted_at)->format('Y-m-d H:i:s'));
        // }

        return $next($request);
    }
}

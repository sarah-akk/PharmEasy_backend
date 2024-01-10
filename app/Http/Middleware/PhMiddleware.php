<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PhMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user() &&  Auth::user()->role == 'user'){
            return $next($request);
        }

        else {return response()->json('Your account is Admin');

        }




    }
}

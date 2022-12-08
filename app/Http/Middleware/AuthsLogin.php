<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        return $next($request);

        $secret = DB::table('oauth_clients')
            ->where('id', '97eba4aa-9534-4f63-a43c-2c67b7bb9968')
            ->pluck('secret')
            ->first();

        $request->merge([
            'grant_type' => 'password',
            'client_id' => '97eba4aa-9534-4f63-a43c-2c67b7bb9968',
            'client_secret' => $secret,
        ]);

        return $next($request);

        // $token = $request->header('APP_KEY');

        // if($token !== 'ABCDE'){
        //     return response()->json(["message" => "Unauthorization users"], 401);
        // }

        // return response()->json(["message" => "Unauthorization users"], 400);

    }
}

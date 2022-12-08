<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class SSOController extends Controller
{
    public function LoginSSO(Request $request){
        $request->session()->put("state", $state = Str::random(40)); //protected csrf  state

        $query = http_build_query([
            "client_id" => env("ACCESS_ID_CLIENT"),
            "redirect_uri" => env("APP_URL") . "/callback",
            "response_type" => "code",
            "scope" => "view-user",
            "state" => $state
        ]);
    
        return redirect(env("APP_SERVER_SSO") . "/oauth/authorize?". $query);
    }

    public function CallbackSSO(Request $request){

        $state = $request->session()->pull("state"); //protected csrf  state

        $response = Http::asForm()->post(env("APP_SERVER_SSO") . "/oauth/token", [
            "grant_type" => "authorization_code",
            "client_id" => env("ACCESS_ID_CLIENT"),
            "client_secret" => env("ACCESS_SECRET_CLIENT"),
            "redirect_uri" => env("APP_URL") . "/callback",
            "code" => $request->code
        ]);
    
        $request->session()->put('data_token', $response->json());
        return $response->json();
    }

    public function RefreshToken(Request $request){

        $data = $request->session()->get('data_token');
        
        $response = Http::asForm()->post(env("APP_SERVER_SSO") . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $data['refresh_token'],
            'client_id' => env("ACCESS_ID_CLIENT"),
            'client_secret' => env("ACCESS_SECRET_CLIENT"),
            'scope' => 'view-user',
        ]);

        $request->session()->put('data_token', $response->json());

        return $response->json();

    }

    public function GetUserData(Request $request){
        $data = $request->session()->get('data_token');
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $data['access_token'],
        ])->get(env("APP_SERVER_SSO") . '/api/user');
    
        return $response->json();

    }
    

}

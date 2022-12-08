<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshTokenRepository;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function index(){
        return response()->json(['message' => 'Unauthorization Login! Please Login with Valid Token!'], 401);
    }


    //hanya untuk access token saja, tidak ada refresh token  dan fungsi ini tidak dipakai di client sso
 
    public function login(Request $request){
        //attempt to login
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            //login successful

            $user = Auth::user();
            $response = [];

            $tokenData = $user->createToken('accessToken-API');
            $response['access_token'] = $tokenData->accessToken;
            $response['expires_in'] = $tokenData->token->expires_at->diffInSeconds(Carbon::now());

            return response()->json(['message' => 'Success Login !', 'data' => $response], 200);
        }
         return response()->json(['message' => 'Login Failed!'], 401);
    }

    //hanya untuk access token saja, tidak ada refresh token.. dan fungsi ini tidak dipakai di client sso

    public function logout()
    {
        $token = auth()->user()->token;
        /* --------------------------- revoke access token -------------------------- */
        $token->revoke();
        $token->delete();

        /* -------------------------- revoke refresh token -------------------------- */

        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        return response()->json(['message' => 'Logged out successfully!']);
    }

        
}

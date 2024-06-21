<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use GuzzleHttp\Client;
use App\Http\Requests\UserLoginRequest; 
use Illuminate\Support\Facades\Auth; 
use Laravel\Passport\Client as OClient;
use App\Models\UserVerify;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public $successStatus = 200;
    public function login(UserLoginRequest $request) { 
        try {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                
                if ($request->remember_me) {
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }
                $token->save();

                // $tokenResult = $this->getTokenAndRefreshToken(Auth::User()->email, $request->password);


                // activity('user login')->log($user->user_name.' login to system');
    
                return response()->json([
                    'user' => $user,
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
                ]);
                // $userdata = Auth::user();
                // $oClient = OClient::where('password_client', 1)->first();
                // return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
            } 
            else { 
                return response()->json(['error'=>'Unauthorised'], 401); 
            }
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }

    public function getTokenAndRefreshToken($email, $password) { 
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://192.168.43.8:8000/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);
        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }

    // Logout API (GET)
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            activity('user logout')->log($user->user_name.' logout from the system');
            $user->token()->revoke();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }

    public function verifyAccount(Request $request)
    {
        try {
            $headertoken = $request->header('ValiditiToken');  
            $veridytoken = $request->token;
            $verifyUser = UserVerify::where('token', $veridytoken)->first();
            $currentTime = Carbon::now();
    
            // return response()->json(['error'=>'Sorry your email cannot be identified.',$verifyUser], 401); 
    
            if(!is_null($verifyUser) && UserVerify::where('headertoken', $headertoken)->first() && !$currentTime->gt($verifyUser->expiry_date)){
    
                $user = $verifyUser->user;
                  
                if(!$user->is_email_verified) {
                    $verifyUser->user->is_email_verified = 1;
                    $verifyUser->user->save();
                    return response()->json([
                        "status" => true,
                        "message" => "Your e-mail is verified. You can now login."
                    ]);
                } else {
                    return response()->json(['error'=>'Your e-mail is already verified. You can now login.'], 401); 
                }
            }
            

        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }
}
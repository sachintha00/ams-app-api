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
                $permissions = $user->getAllPermissions()->load('routes');
                $routesmain = $user->getAllPermissions()->pluck('routes')->flatten()->unique()->toArray(); 

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                
                if ($request->remember_me) {
                    $token->expires_at = Carbon::now()->addWeeks(1);
                }
                $token->save();
                activity('user login')->log($user->user_name.' login to system');
    
                return response()->json([
                    'user' => $user,
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
                    'permissions' => $permissions,
                    'routesmain' => $routesmain
                ]);
                // $userdata = Auth::user();
                // $oClient = OClient::where('password_client', 1)->first();
                // return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
            } 
            else { 
                return response()->json(['error'=>'Unauthorised'], 401); 
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [ 
                'user_name' => 'required', 
                'email' => 'required|email|unique:users',
                'contact_no' => 'required', 
                'password' => 'required', 
                'c_password' => 'required|same:password', 
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $password = $request->password;
            $input = $request->all(); 
            $input['password'] = bcrypt($input['password']); 
            $user = User::create($input); 
            $oClient = OClient::where('password_client', 1)->first();
            return $this->getTokenAndRefreshToken($oClient, $user->email, $password);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } 
    }
    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) { 
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://192.168.8.184:8000/oauth/token', [
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
    // Profile API (GET)
    public function profile(){
        
        try {
            $userdata = Auth::user();

            return response()->json([
                "status" => true,
                "message" => "Profile data",
                "data" => $userdata
            ]);
            
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } 
    }

    // Logout API (GET)
    public function logout(){

        try {
            $token = auth()->user()->token();

            /* --------------------------- revoke access token -------------------------- */
            $token->revoke();
            // $token->delete();
    
            /* -------------------------- revoke refresh token -------------------------- */
            $refreshTokenRepository = app(RefreshTokenRepository::class);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
    
            return response()->json([
                "status" => true,
                "message" => "User logged out"
            ]);
            
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
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
            
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        } 
    }
}
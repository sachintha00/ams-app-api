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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public $successStatus = 200;

    // public function login(Request $request) { 
    //     // Validate user input
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     // If validation fails, return validation errors
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $credentials = $request->only('email', 'password');
        

    //     // Call your pre-login API endpoint
    //     $response = Http::timeout(15)->post('http://213.199.44.42:8000/api/v1/tenant_user_login', [
    //         'email' => $credentials['email'],
    //         'password' => $credentials['password'],
    //     ]);

    //     // Check if the request was successful
    //     if ($response->successful()) {
    //         // Assign response data to a PHP variable
    //         $responseData = $response->json();

    //         // Access specific data from the response
    //         $userdata = $responseData['user'];
    //         $database = $userdata['tenant_db_name'];
            
    //         // Set the database connection dynamically
    //         Config(['database.connections.pgsql.database' => $database]);
    //         DB::reconnect('pgsql');
    //         // dd(DB::getDatabaseName());

    //         if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
    //             // $oClient = OClient::where('password_client', 1)->first();
    //             // $http = new Client;
    //             // $response = $http->request('POST', 'http://192.168.8.184:8000/oauth/token', [
    //             //     'headers' => [
    //             //         'X-API-Key' => '{{token}}',
    //             //         'Dbname' => $database,
    //             //     ],
    //             //     'form_params' => [
    //             //         'grant_type' => 'password',
    //             //         'client_id' => $oClient->id,
    //             //         'client_secret' => $oClient->secret,
    //             //         'username' => request('email'),
    //             //         'password' => request('password'),
    //             //         'scope' => '*',
    //             //     ],
    //             // ]);

    //             // $result = json_decode((string) $response->getBody(), true);
    //             // return response()->json($result, $this->successStatus);
    //             $user = Auth::user();
    //             $token = $user->createToken('MyApp')->accessToken;
    
    //             return response()->json(['user' => $user, 'access_token' => $token, 'potral' => $responseData ], 200);
    //         } 
    //         else { 
    //             return response()->json(['error'=>'Unauthorised'], 401); 
    //         } 
    //     }else {
    //         // Handle the case where the API request failed
    //         return response()->json(['error'=>'Unauthorised'], 401); 
    //         // Log error or perform error handling
    //     }
    // }
    
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

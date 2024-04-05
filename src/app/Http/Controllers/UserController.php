<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Mail; 
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use App\Http\Requests\CreateSubUsersRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view user',['only' => ['index']]);
        $this->middleware('permission:create user',['only' => ['store']]);
        $this->middleware('permission:update user',['only' => ['update']]);
        $this->middleware('permission:delete user',['only' => ['destroy']]);
        $this->middleware('permission:user status change',['only' => ['changestatus']]);
        $this->middleware('permission:user password reset',['only' => ['passwordreset']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $UsersWithRoles = User::with('roles')->get();
        $Role = Role::all();
        $activeuser = Auth::user();

        // save activity log
        activity()
            ->causedBy($activeuser)
            ->log($activeuser->user_name.' View user page');

        //return json response
        return response()->json([
            "status" => true, 
            'Users' => $UsersWithRoles,
            'Role' => $Role
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubUsersRequest $request)
    {
        try {
            // $imageName = Str::random(10).$request->user_name.".".$request->profie_image->getClientOriginalExtension();
                
            $input = $request->all();
            $password = Str::random(8); 
            $input['password'] = bcrypt($password);
            // $input['profie_image'] = $imageName;
            $user = User::create($input);

            // Save Image in Storage folder
            // Storage::disk('public')->put($imageName, file_get_contents($request->profie_image));
            
            $user->syncRoles($request->roles);
            
            $headertoken = Str::random(10);
            $verifytoken = Str::random(6);
            $expiration = Carbon::now()->addDay(1);
    
            UserVerify::create([
                'user_id' => $user->id,
                'headertoken' => $headertoken,
                'token' => $verifytoken,
                'expiry_date' => $expiration
                ]);
    
            Mail::send('email.subuseremailVerificationEmail', ['token' => $verifytoken, 'password' => $password], function($message) use($request){
                $message->to($request->email);
                $message->subject('Email Verification Mail');
            });

            // Return Json Response
            return response()->json([
                'message' => "User Account successfully created."
            ],200);
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateuserdata(Request $request, $userid)
    {
        try {
            $user = User::findOrFail($userid);

            if(!$user){
              return response()->json([
                'message'=>'User Not Found.'
              ],404);
            }

            $user->user_name = $request->user_name;
            $user->email = $request->email;
            $user->contact_no = $request->contact_no;
    
            // Update user status
            $user->save();

            $user->syncRoles($request->roles);
            
            $successmessage = "Your account details is updated";
            Mail::send('email.updateuserdetails', ['successmessage' => $successmessage], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Acccount details update Mail');
            });

            // Return Json Response
            return response()->json([
                'message' => "User Account Status Updated successfully."
            ],200);
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userid)
    {
        try {
            $user = User::findOrFail($userid);
            $successmessage = "admin is delete Your account.";
            Mail::send('email.accountdelete', ['successmessage' => $successmessage], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Acccount details update Mail');
            });
            
            $user->delete();

            // Return Json Response
            return response()->json([
                'message' => "User Account Deleted successfully."
            ],200);
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function changestatus(Request $request, $userid)
    {
        try {
            $user = User::findOrFail($userid);

            $user->status = $request->status;
    
            // Update user status
            $user->save();

            if($request->status == 1){
                $statusmessage = "your account is activate";
                Mail::send('email.accountstatusEmail', ['statusmessage' => $statusmessage], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Password reset email');
                });
            }elseif ($request->status == 1) {
                $statusmessage = "your account is deactivate";
                Mail::send('email.accountstatusEmail', ['statusmessage' => $statusmessage], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Password reset email');
                });
            }

            // Return Json Response
            return response()->json([
                'message' => "User Account Status Updated successfully."
            ],200);
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function passwordreset(Request $request, $userid)
    {
        try {
            $user = User::findOrFail($userid);
            $password = Str::random(8); 
            $user->password = bcrypt($password);

            // reset password
            $user->save();
      
            Mail::send('email.userpasswordresetEmail', ['password' => $password], function($message) use($user){
                  $message->to($user->email);
                  $message->subject('Password reset email');
            });
    
            // Return Json Response
            return response()->json([
                'message' => "password is successfully reset."
            ],200);
        } catch (\Throwable $th) {
                    return response()->json([
                        'status' => false,
                        'message' => $th->getMessage()
                    ], 500);
        }
    }
}

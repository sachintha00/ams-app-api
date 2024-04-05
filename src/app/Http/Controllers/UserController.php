<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Mail; 
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use App\Http\Requests\CreateSubUsersRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $UsersWithRoles = User::with('roles')->get();

        //return json response
        return response()->json([
            "status" => true, 
            'UserRoles' => $UsersWithRoles
        ],200);
    }

    /**
     * Display a listing of the resource.
     */
    public function create()
    {
        $Role = Role::pluck('name','name')->all();

        //return json response
        return response()->json([
            "status" => true,
            'Role' => $Role
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubUsersRequest $request)
    {
        try {
            $input = $request->all();
            $password = Str::random(8); 
            $input['password'] = bcrypt($password); 
            $user = User::create($input);
             
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

        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Display the specified resource.
    */
    public function edit(string $id)
    {
        $user = Auth::user();
        $Role = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();

        //return json response
        return response()->json([
            "status" => true,
            'Role' => $Role,
            'userRoles' => $userRoles
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
        try {
            $input = $request->all();
            $password = Str::random(8); 
            $input['password'] = bcrypt($password); 
            $user = User::create($input);
            
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

        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userid)
    {
        $user = User::findOrFail($userid);
        $user->delete();


        // Return Json Response
        return response()->json([
            'message' => "User Account Deleted successfully."
        ],200);
    }
}

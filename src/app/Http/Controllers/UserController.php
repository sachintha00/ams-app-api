<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Support\Str;
use Mail; 
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use App\Http\Requests\CreateSubUsersRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Services\MasterEntryService;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    protected $MasterEntryService;

    public function __construct(MasterEntryService $MasterEntryService)
    {
        $this->middleware('permission:Users',['only' => ['index']]);
        $this->middleware('permission:create user',['only' => ['store']]);
        $this->middleware('permission:update user',['only' => ['update']]);
        $this->middleware('permission:delete user',['only' => ['destroy']]);
        $this->middleware('permission:user status change',['only' => ['changestatus']]);
        $this->middleware('permission:user password reset',['only' => ['passwordreset']]);
        $this->MasterEntryService = $MasterEntryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $UsersWithRoles = User::with('roles')->get();
        $Role = Role::all();
        $activeuser = Auth::user();
        $AllDesignations = $this->MasterEntryService->getAllDesignations();

        // save activity log
        activity()
            ->causedBy($activeuser)
            ->log($activeuser->user_name.' View user page');

        //return json response
        return response()->json([
            "status" => true, 
            'Users' => $UsersWithRoles,
            'Role' => $Role,
            'AllDesignations' => $AllDesignations
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

            $user = Auth::user();
            $tenant_db_name = $user->tenant_db_name;

            if ($request->hasfile('profile_image')) {
                $file = $request->file('profile_image');
                $name = time() . '_' . $file->getClientOriginalName();
                // Store the file and get the path
                $filePath = $file->storeAs('public/uploads/profile_image', $name);
                // Add the path to the array
                $input['profie_image'] = $name;
            }

            // Call your pre-login API endpoint
            $response = Http::timeout(15)->post('http://213.199.44.42:8000/api/v1/tenant_user_register', [
                'user_name' => $input['user_name'],
                'name' => $input['name'],
                'contact_no' => $input['contact_no'],
                'contact_person' => $input['contact_person'],
                'address' => $input['address'],
                'user_description' => $input['user_description'],
                'tenant_db_name' => $tenant_db_name,
                'email' => $input['email'],
                'password' => $input['password'],
            ]);
            if ($response->successful()) {
                $user = User::create($input);
                $user->syncRoles($request->roles);
    
                Mail::send('email.subuseremailVerificationEmail', ['password' => $password], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('Email Verification Mail');
                });
    
                // Return Json Response
                return response()->json([
                    'message' => "User Account successfully created."
                ],200);
            }else {
                // Handle the case where the API request failed
                return response()->json(['error'=>'Unauthorised'], 401); 
                // Log error or perform error handling
            }
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
            $user->name = $request->name;
            $user->contact_no = $request->contact_no;
            $user->user_description = $request->user_description;
    
            // Update user status
            $user->save();

            // Remove all roles from the user
            $user->roles()->detach();
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

            // Remove all roles from the user
            $user->roles()->detach();
            
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

            $user->is_user_blocked = $request->is_user_blocked;
     
            // Update user status
            $user->save();

            if($request->is_user_blocked == 1){
                $statusmessage = "your account is activate";
                Mail::send('email.accountstatusEmail', ['statusmessage' => $statusmessage], function($message) use($user){
                    $message->to($user->email);
                    $message->subject('Password reset email');
                });
            }elseif ($request->is_user_blocked == 1) {
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

    public function retrieveAllUserWithPaginate(Request $request){
        try {
            $databaseName = DB::getDatabaseName();
            $page = $request->get('page', 1);
        
            $cacheKey = $databaseName . '_users_page_' . $page;
        
            $users = Cache::get($cacheKey);
            
            if (!$users) {
                $users = User::paginate(10);
                Cache::put($cacheKey, $users);
            }
        
            return response()->json($users);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error occurred'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function retrieveAllUserFromQuerySearch(Request $request){
        try {
            $databaseName = DB::getDatabaseName();
            $searchQuery = $request->input('query');
            $page = $request->get('page', 1);
        
            $cacheKey = $databaseName . '_users_page_' . $page . '_query_' . $searchQuery;
        
            $users = Cache::get($cacheKey);
        
            if (!$users) {
                $users = User::where('name', 'ilike', "$searchQuery%")->paginate(10);
                Cache::put($cacheKey, $users);
            }
        
            return response()->json($users);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error occurred'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
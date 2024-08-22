<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth; 
use Exception;
use App\Models\test_wijart;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Role',['only' => ['index']]);
        $this->middleware('permission:create role',['only' => ['create','store']]);
        $this->middleware('permission:update role',['only' => ['update','edite']]);
        $this->middleware('permission:delete role',['only' => ['destroy']]);
        $this->middleware('permission:give permissions to role',['only' => ['addPermissionToRole','givePermissionToRole','removePermissionFromRole']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Role = Role::with('permissions')->get();
        $Permission = Permission::get();
        $user = Auth::user();

        // save activity log
        activity()
            ->causedBy($user)
            ->log($user->user_name.' View Role page');

        //return json response
        return response()->json([
            "status" => true,
            'Role' => $Role,
            'Permission' => $Permission,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'unique:roles,name'
                ]
            ]);

            Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Return Json Response
            return response()->json([
                'message' => "Role successfully created."
            ],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Find product
            $Role = Role::find($id);
            if(!$Role){
              return response()->json([
                'message'=>'Role Not Found.'
              ],404);
            }

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'unique:roles,name'
                ]
            ]);
            
            //echo "request : $request->image";
            $Role->name = $request->name;
            $Role->description = $request->description;
      
            // Update Product
            $Role->save();
      
            // Return Json Response
            return response()->json([
                'message' => "Role successfully updated."
            ],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //Details
            $Role = Role::find($id);

            if(!$Role){
                return response()->json([
                'message'=>'Role Not Found.'
                ],404);
            }

            // Delete Product
            $Role->delete();

            // Return Json Response
            return response()->json([
                'message' => "Role successfully Deleted."
            ],200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function addPermissionToRole(string $id)
    {
        //Details
        $Permission = Permission::get();
        $role = Role::find($id);

        if(!$role){
            return response()->json([
              'message'=>'Role Not Found.'
            ],404);
        }

        //return json response
        return response()->json([
            "status" => true,
            'Role' => $role,
            'Permission' => $Permission
        ],200);
    }

    public function givePermissionToRole(Request $request, string $id)
    {
        $request->validate([
            'permission' => [
                'required'
            ]
        ]);
        //Details
        $role = Role::find($id);

        if(!$role){
            return response()->json([
              'message'=>'Role Not Found.'
            ],404);
        }

        $role->givePermissionTo($request->permission);

        // Return Json Response
        return response()->json([
            'message' => "Permissions added to role successfully."
        ],200);
    }

    public function removePermissionFromRole(Request $request, string $id)
    {
        $request->validate([
            'permission' => [
                'required'
            ]
        ]);
        //Details
        $role = Role::find($id);

        if(!$role){
            return response()->json([
              'message'=>'Role Not Found.'
            ],404);
        }

        $role->revokePermissionTo($request->permission);

        // Return Json Response
        return response()->json([
            'message' => "Permissions remove from the role successfully."
        ],200);
    }
}

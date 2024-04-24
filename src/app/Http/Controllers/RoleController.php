<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view role',['only' => ['index']]);
        $this->middleware('permission:create role',['only' => ['create','store']]);
        $this->middleware('permission:update role',['only' => ['update','edite']]);
        $this->middleware('permission:delete role',['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Role = Role::get();

        //return json response
        return response()->json([
            "status" => true,
            'Role' => $Role
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
                'name' => $request->name
            ]);

            // Return Json Response
            return response()->json([
                'message' => "Role successfully created."
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
      
            // Update Product
            $Role->save();
      
            // Return Json Response
            return response()->json([
                'message' => "Role successfully updated."
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
    public function destroy(string $id)
    {
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
    }

    public function addPermissionToRole(string $id)
    {
        //Details
        $Permission = Permission::get();
        $role = Role::find($id);
        $rolePermissions =DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id', $role->id)
                                ->pluck('role_has_permissions.permission_id','role_haas_permissions.permission_id')
                                ->all();

        if(!$role){
            return response()->json([
              'message'=>'Role Not Found.'
            ],404);
        }

        //return json response
        return response()->json([
            "status" => true,
            'Role' => $role,
            'Permission' => $Permission,
            'rolePermissions' => $rolePermissions
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
}

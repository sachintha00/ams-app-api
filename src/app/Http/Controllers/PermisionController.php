<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Permission = Permission::get();

        //return json response
        return response()->json([
            "status" => true,
            'Permission' => $Permission
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
                    'unique:permissions,name'
                ]
            ]);

            Permission::create([
                'name' => $request->name
            ]);

            // Return Json Response
            return response()->json([
                'message' => "permission successfully created."
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
            $Permission = Permission::find($id);
            if(!$Permission){
              return response()->json([
                'message'=>'Permission Not Found.'
              ],404);
            }

            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'unique:permissions,name'
                ]
            ]);
      
            //echo "request : $request->image";
            $Permission->name = $request->name;
      
            // Update Product
            $Permission->save();
      
            // Return Json Response
            return response()->json([
                'message' => "Permission successfully updated."
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
        $Permission = Permission::find($id);

        if(!$Permission){
            return response()->json([
              'message'=>'Permission Not Found.'
            ],404);
        }

        // Delete Product
        $Permission->delete();

        // Return Json Response
        return response()->json([
            'message' => "Permission successfully Deleted."
        ],200);
    }
}

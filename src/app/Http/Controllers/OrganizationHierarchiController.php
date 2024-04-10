<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrganizationHierarchi;

class OrganizationHierarchiController extends Controller
{
    public function insertNewNodeToOrganizationHierarchi(Request $request){
        $validatedData = $request->validate([
            'parent_node_id' => 'required|integer',
            'level' => 'required|integer',
            'data' => 'required|array',
            // 'data.name' => 'required|string',
        ]);

        OrganizationHierarchi::create($validatedData);

        return response()->json(['message' => 'New organization node inserted successfully'], 201);
    }

    public function retrieveOrganizationHierarchi(Request $request){

        $organizationHierarchi = OrganizationHierarchi::all();

        return response()->json(['message' => 'New organization node inserted successfully', 'data' => $organizationHierarchi], 201);
    }
    public function updateNodeFromOrganizationHierarchi(Request $request){

        $organizationId = $request->query('organizationId');

        $validatedData = $request->validate([
            'parent_node_id' => 'required|integer',
            'level' => 'required|integer',
            'data' => 'required|array',
            // 'data.name' => 'required|string',
        ]);
    
        $organizationHierarchi = OrganizationHierarchi::findOrFail($organizationId);
        $organizationHierarchi->update($validatedData);
    
        return response()->json(['message' => 'Organization node updated successfully', 'data' => $organizationHierarchi], 200);
    }

    public function removeNodeFromOrganizationHierarchi(Request $request){

        $organizationId = $request->query('organizationId');
        
        $organizationHierarchi = OrganizationHierarchi::findOrFail($organizationId);
        $organizationHierarchi->delete();
    
        return response()->json(['message' => 'Organization node deleted successfully'], 200);
    }
}
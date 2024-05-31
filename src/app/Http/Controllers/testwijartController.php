<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\test_wijart;

class testwijartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wijart = test_wijart::all();

        return response()->json([
            'wijart' => $wijart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $wijart = test_wijart::create($input);

        // Return Json Response
        return response()->json([
            'message' => "successfully created."
        ],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

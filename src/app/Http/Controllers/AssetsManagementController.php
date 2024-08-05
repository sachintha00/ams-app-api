<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MasterEntryService;

class AssetsManagementController extends Controller
{
    protected $MasterEntryService;

    public function __construct(MasterEntryService $MasterEntryService)
    {
        $this->MasterEntryService = $MasterEntryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $allassesttype = $this->MasterEntryService->getAllAssetsTypes();
            $allavailabilitytype = $this->MasterEntryService->getAllAvailabilityTypes();
            $Allassetcategories = $this->MasterEntryService->getAllAssetCategories();

            foreach($Allassetcategories as $AllAssetCategories){
                $AllAssetCategories->sub_categories = json_decode($AllAssetCategories->sub_categories);
            }
            return response()->json([
                "status" => true,
                'allassesttype' => $allassesttype,
                'allavailabilitytype' => $allavailabilitytype,
                'Allassetcategories' => $Allassetcategories,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

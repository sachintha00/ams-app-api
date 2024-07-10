<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MasterEntryService;
use App\Services\SupplairService;
use App\Services\AssetRequisitionService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\CreateAssestRequisitionRequest;

class AssestRequisitionController extends Controller
{
    protected $MasterEntryService;
    protected $SupplairService;
    protected $AssetRequisitionService;

    public function __construct(MasterEntryService $MasterEntryService, SupplairService $SupplairService, AssetRequisitionService $AssetRequisitionService)
    {
        $this->MasterEntryService = $MasterEntryService;
        $this->SupplairService = $SupplairService;
        $this->AssetRequisitionService = $AssetRequisitionService;
    }
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {
            $user = Auth::user();
            $userid = $user->id;
            $allUserAssetRequisitions = $this->AssetRequisitionService->getUserAssetRequisition($userid);

            foreach($allUserAssetRequisitions as $allUserAssetRequisition){
                $allUserAssetRequisition->items = json_decode($allUserAssetRequisition->items);
            }

            $allassesttype = $this->MasterEntryService->getAllAssetsTypes();
            $allitemtype = $this->MasterEntryService->getAllItemTypes();
            $allperiodtype = $this->MasterEntryService->getAllPeriodTypes();
            $allavailabilitytype = $this->MasterEntryService->getAllAvailabilityTypes();
            $allprioritytype = $this->MasterEntryService->getAllPriorityTypes();
            $allsupplair = $this->SupplairService->getAllSupplair();

            return response()->json([
                "status" => true,
                'allUserAssetRequisition' => $allUserAssetRequisitions,
                'allassesttype' => $allassesttype,
                'allitemtype' => $allitemtype,
                'allPeriodtype' => $allperiodtype,
                'allavailabilitytype' => $allavailabilitytype,
                'allprioritytype' => $allprioritytype,
                'allsupplair' => $allsupplair
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
        $input = $request->all();
        $input['user_id'] = Auth::id();

        $currentTime = Carbon::now();
        $input['current_time'] = $currentTime;

        try {
            $this->AssetRequisitionService->createAssetRequisition($input); 

            if ($input['requisition_status'] == 'saved'){
                return response()->json(['message' => 'Requisition saved successfully'], 201);
            }else{
                return response()->json(['message' => 'Requisition submitted successfully'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit requisition', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update assest Requisition status
    */
    public function statusupdate(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();

        $currentTime = Carbon::now();
        $input['current_time'] = $currentTime;

        try {
            $this->AssetRequisitionService->submitAssetRequisition($input); 

            return response()->json(['message' => 'Requisition submitted successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit requisition', 'message' => $e->getMessage()], 500);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProcurementService;

class ProcurementController extends Controller
{

    protected $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }
    public function getProcurementStaffDetails()
    {
        try {
            $results = $this->procurementService->getProcurementStaffDetails();

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve procurement staff ' . $e->getMessage()], 500);
        }
    }

    public function addMemberToProcurementStaff(Request $request)
    {
        try {
            $userId = $request->input('user_id', null);
            $assetClassId = $request->input('asset_class_id');

            $result = $this->procurementService->addUpdateMemberToProcurementStaff(
                $userId,
                $assetClassId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully added or updated procurement staff'], 200);
            }else{
                return response()->json(['error' => 'Failed to add or update '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add or update ' . $e->getMessage()], 500);
        }
    }

    public function updateMemberToProcurementStaff(Request $request)
    {
        try {
            $staffId = $request->input('staff_id', null);
            $userId = $request->input('user_id', null);
            $assetClassId = $request->input('asset_class_id');

            $result = $this->procurementService->addUpdateMemberToProcurementStaff(
                $userId,
                $assetClassId,
                $staffId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully added or updated procurement staff'], 200);
            }else{
                return response()->json(['error' => 'Failed to add or update '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add or update ' . $e->getMessage()], 500);
        }
    }


    public function removeMemberFromProcurementStaff(Request $request, $procurement_id)
    {
        try {
            $procurementId = (int)$procurement_id;

            $result = $this->procurementService->removeMemberFromProcurementStaff(
                $procurementId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully remove procurement staff'], 200);
            }else{
                return response()->json(['error' => 'Failed to remove '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove ' . $e->getMessage()], 500);
        }
    }
}
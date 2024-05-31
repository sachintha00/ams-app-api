<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ProcurementRepository
{
    public function getProcurementStaffDetails($id=0)
    {
        DB::select('CALL STORE_PROCEDURE_GET_PROCUREMENT_STAFF_DETAILS()');
        $procurementStaffArray = DB::table('procurement_staff_details_from_store_procedure')->select('*')->get();
        
        return $procurementStaffArray;
    }

    public function addUpdateMemberToProcurementStaff($userId=0, $assetClassId=0, $staffId=0)
    {
        DB::select('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_PROCUREMENT_STAFF(?, ?, ?)',  [$userId, $assetClassId, $staffId]);
        $procurementStaffResponse = DB::table('procurement_staff_response_from_store_procedure')->select('*')->get();
        
        return $procurementStaffResponse;
    }

    public function removeMemberFromProcurementStaff($procurementId=0)
    {
        try {
            $procurementStaffResponse = DB::select('CALL STORE_PROCEDURE_DELETE_PROCUREMENT_STAFF(?)', [$procurementId]);
            
            return "SUCCESS";
    
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            
        } catch (\Exception $e) {
            // Catch all other exceptions
            return $e->getMessage();
        }
    }
}
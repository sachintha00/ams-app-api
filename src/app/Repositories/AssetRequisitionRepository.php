<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AssetRequisitionRepository
{
    /**
     * Create an asset requisition and related items.
     *
     * @param array $data
     * @return void
     */
    public function createAssetRequisition(array $data)
    {
        DB::beginTransaction();

        try {
            // Call the stored procedure
            DB::statement('CALL submit_asset_requisition(?, ?, ?, ?, ?, ?)', [
                $data['requisition_id'],
                $data['user_id'],
                $data['requisition_date'],
                $data['requisition_status'], 
                $data['current_time'],
                json_encode($data['items'])
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function submitAssetRequisition(array $data)
    {
        DB::beginTransaction();

        try {
            // Call the stored procedure
            DB::statement('CALL submit_asset_requisition(?, ?, NULL, ?, NULL, NULL)', [
                $data['requisition_id'],
                $data['user_id'],
                $data['requisition_status']
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getUserAssetRequisition($id)
    {
        DB::select('CALL STORE_PROCEDURE_GET_ALL_USER_ASSET_REQUISITIONS(?)', [$id]);
        $allUserAssetRequisition = DB::table('get_all_user_asset_requisitions_from_store_procedure')->select('*')->get();
        
        return $allUserAssetRequisition;
    }

    public function getAllApprovedAssetRequisition($userId)
    {
        try {
            DB::select('CALL STORE_PROCEDURE_GET_APPROVED_ASSET_REQUISITION(?)',[$userId]);
            
            $allApprovedAssetRequisitions = DB::table('approved_asset_requisitions_from_store_procedure')->select('*')->get();
            
            $formattedData = [];
        
            foreach ($allApprovedAssetRequisitions as $requisition) {
                $formattedItems = json_decode($requisition->requisition_data, true); 
                
                $formattedData[] = [
                    'requisition_data' => $formattedItems,
                ];
            }

            return $formattedData[0];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

}
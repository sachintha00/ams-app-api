<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AssetsManagementRepository
{
    /**
     * Create an asset requisition and related items.
     *
     * @param array $data
     * @return void
     */
    public function createAssetRegister(array $data)
    {
        $p_thumbnail_image = json_encode($data['p_thumbnail_image']);
        $p_assets_document = json_encode($data['p_assets_document']);
        $p_purchase_document = json_encode($data['p_purchase_document']);
        $p_insurance_document = json_encode($data['p_insurance_document']);
        $p_asset_details = is_array($data['p_asset_details']) ? json_encode($data['p_asset_details']) : $data['p_asset_details'];

        DB::beginTransaction();

        try {
            // Call the stored procedure
            DB::statement('CALL create_full_asset_register(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $p_thumbnail_image,
                $data['p_register_date'],
                $data['p_assets_type'],
                $data['p_category'],  
                $data['p_sub_category'],
                $data['p_assets_value'],
                $p_assets_document,
                $data['p_supplier'],
                $data['p_purchase_order_number'], 
                $data['p_purchase_cost'],
                $data['p_purchase_type'],
                $data['p_received_condition'],
                $data['p_warranty'],
                $data['p_other_purchase_details'], 
                $p_purchase_document,
                $data['p_insurance_number'],
                $p_insurance_document,
                $data['p_expected_life_time'],
                $data['p_depreciation_value'], 
                $data['p_registered_by'],
                $data['p_deleted'],
                $data['p_deleted_at'],
                $data['p_deleted_by'],
                $p_asset_details
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllAssets() 
    {
        try {
            DB::select('CALL STORE_PROCEDURE_RETRIEVE_ASSETS()');
            $allAssetsAsArray = DB::table('assets_from_store_procedure')->select('*')->get();
            
            return $allAssetsAsArray;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

}
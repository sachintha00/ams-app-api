<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SupplairRepository
{
    public function getAllSupplair()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_SUPPLAIR()');
        $allSupplairAsArray = DB::table('suppliers_from_store_procedure')->select('*')->get();
        
        return $allSupplairAsArray;
    }

    public function getSupplierRegNo()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_SUPPLIER_REG_NO()');
        $allSupplairAsArray = DB::table('supplier_reg_no_from_store_procedure')->select('*')->get();
        
        return $allSupplairAsArray[0];
    }

    public function addNewSupplier(
        $p_name,
        $p_address,
        $p_description,
        $p_supplier_asset_classes,
        $p_supplier_rating,
        $p_supplier_bussiness_name,
        $p_supplier_bussiness_register_no,
        $p_supplier_primary_email,
        $p_supplier_secondary_email,
        $p_supplier_br_attachment,
        $p_supplier_website,
        $p_supplier_tel_no,
        $p_supplier_mobile,
        $p_supplier_fax,
        $p_supplier_city,
        $p_supplier_location_latitude,
        $p_supplier_location_longitude,
        $p_contact_no,
        $p_id,
        $p_supplier_register_status
    )
    {
        DB::select('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_SUPPLIER(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $p_name,
            $p_address,
            $p_description,
            $p_supplier_asset_classes,
            $p_supplier_rating,
            $p_supplier_bussiness_name,
            $p_supplier_bussiness_register_no,
            $p_supplier_primary_email,
            $p_supplier_secondary_email,
            $p_supplier_br_attachment,
            $p_supplier_website,
            $p_supplier_tel_no,
            $p_supplier_mobile,
            $p_supplier_fax,
            $p_supplier_city,
            $p_supplier_location_latitude,
            $p_supplier_location_longitude,
            $p_contact_no,
            $p_id,
            $p_supplier_register_status
        ]);
        $allSupplairAsArray = DB::table('supplier_add_response_from_store_procedure')->select('*')->get();
        return $allSupplairAsArray;
    }
}

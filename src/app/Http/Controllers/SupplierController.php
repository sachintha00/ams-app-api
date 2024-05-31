<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Services\MasterEntryService;
use App\Services\SupplairService;

class SupplierController extends Controller
{
    protected $SupplairService;

    public function __construct( SupplairService $SupplairService)
    {
        $this->SupplairService = $SupplairService;
    }

    public function getSuppliers(){
        try {
            $results = $this->SupplairService->getAllSupplair();

            foreach($results as $result){
                $result->contact_no = json_decode($result->contact_no);
            }

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve suppliers ' . $e->getMessage()], 500);
        }
    }

    public function getSupplierRegNo(){
        try {
            $result = $this->SupplairService->getSupplierRegNo();

            return response()->json(['status'=>"success", 'data' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve reg no ' . $e->getMessage()], 500);
        }
    }

    public function addOrUpdateSupplier(Request $request){
        try {
            $p_id = $request->input('p_id', null);
            $p_name = $request->input('p_name');
            $p_address = $request->input('p_address');
            $p_description = $request->input('p_description');
            $p_supplier_asset_classes = json_encode($request->input('p_supplier_asset_classes'));
            $p_supplier_rating = $request->input('p_supplier_rating');
            $p_supplier_bussiness_name = $request->input('p_supplier_bussiness_name');
            $p_supplier_bussiness_register_no = $request->input('p_supplier_bussiness_register_no');
            $p_supplier_primary_email = $request->input('p_supplier_primary_email');
            $p_supplier_secondary_email = $request->input('p_supplier_secondary_email');
            $p_supplier_br_attachment = $request->input('p_supplier_br_attachment');
            $p_supplier_website = $request->input('p_supplier_website');
            $p_supplier_tel_no = $request->input('p_supplier_tel_no');
            $p_supplier_mobile = $request->input('p_supplier_mobile');
            $p_supplier_fax = $request->input('p_supplier_fax');
            $p_supplier_city = $request->input('p_supplier_city');
            $p_supplier_location_latitude = $request->input('p_supplier_location_latitude');
            $p_supplier_location_longitude = $request->input('p_supplier_location_longitude');
            $p_contact_no = json_encode($request->input('p_contact_no'));
            $p_supplier_register_status = $request->input('p_supplier_register_status', 'PENDING');

            $result = $this->SupplairService->addNewSupplier(
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
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfuly added or updated supplier'], 200);
            }else{
                return response()->json(['error' => 'Failed to retrieve reg no ' ], 500);
            }

            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve reg no ' . $e->getMessage()], 500);
        }
    }


    public function retrieveSupplierFromQuerySearch(Request $request)
    {
        try {
            $searchQuery = $request->input('query');
            $suppliers = SupplierModel::where('name', 'ilike', "$searchQuery%")->paginate(10);
        
            return response()->json($suppliers);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error occurred'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function removeSupplier(Request $request, $supplier_id)
    {
        try {
            $supplierId = (int)$supplier_id;

            $result = $this->SupplairService->removeSupplier(
                $supplierId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully remove supplier'], 200);
            }else{
                return response()->json(['error' => 'Failed to remove '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove ' . $e->getMessage()], 500);
        }
    }
}

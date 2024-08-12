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
        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $name = time() . '_' .$input['name']. $file->getClientOriginalName();
            $file->move(public_path('uploads/thumbnail_image'), $name);
            $thumbnailImage = 'uploads/thumbnail_image/' . $name;
            $p_thumbnail_image = $thumbnailImage;
        }

        $assetsDocument = [];
        if ($request->hasfile('assets_document')) {
            foreach ($request->file('assets_document') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $name);
                $assetsDocument[] = 'uploads/assets_document/' . $name;
            }
        }
        $p_assets_document = $assetsDocument;

        $purchaseDocument = [];
        if ($request->hasfile('purchase_document')) {
            foreach ($request->file('purchase_document') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $name);
                $purchaseDocument[] = 'uploads/purchase_document/' . $name;
            }
        }
        $p_purchase_document = $purchaseDocument;

        $insuranceDocument = [];
        if ($request->hasfile('insurance_document')) {
            foreach ($request->file('insurance_document') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $name);
                $insuranceDocument[] = 'uploads/insurance_document/' . $name;
            }
        }
        $p_insurance_document = $insuranceDocument;

        $p_qr_code = $request->input('qr_code');
        $p_register_date = $request->input('register_date');
        $p_assets_type = $request->input('assets_type');
        $p_category = $request->input('category');
        $p_sub_category = $request->input('sub_category');
        $p_assets_value = $request->input('assets_value');
        $p_supplier = $request->input('supplier');
        $p_purchase_order_number = $request->input('purchase_order_number');
        $p_purchase_cost = $request->input('purchase_cost');
        $p_purchase_type = $request->input('purchase_type');
        $p_received_condition = $request->input('received_condition');
        $p_warranty = $request->input('warranty');
        $p_other_purchase_details = $request->input('other_purchase_details');
        $p_insurance_number = $request->input('insurance_number');
        $p_expected_life_time = $request->input('expected_life_time');
        $p_depreciation_value = $request->input('depreciation_value');
        $p_registered_by = $request->input('registered_by');
        $p_deleted = $request->input('deleted', false); // Default to false if not provided
        $p_deleted_at = $request->input('deleted_at', null);
        $p_deleted_by = $request->input('deleted_by', null);
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MasterEntryService;
use App\Services\AssetsManagementService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AssetsManagementController extends Controller
{
    protected $MasterEntryService;
    protected $AssetsManagementService;

    public function __construct(MasterEntryService $MasterEntryService, AssetsManagementService $AssetsManagementService)
    {
        $this->MasterEntryService = $MasterEntryService;
        $this->AssetsManagementService = $AssetsManagementService;
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
            $allassests = $this->AssetsManagementService->getAllAssets();

            foreach($Allassetcategories as $AllAssetCategories){
                $AllAssetCategories->sub_categories = json_decode($AllAssetCategories->sub_categories);
            }

            // $allassests->thumbnail_image = array_map(function($path) {
            //     return Storage::url($path);
            // }, $allassests->thumbnail_image);

            return response()->json([
                "status" => true,
                'allassesttype' => $allassesttype,
                'allavailabilitytype' => $allavailabilitytype,
                'Allassetcategories' => $Allassetcategories,
                'Allassests' => $allassests
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
        try {

            $input = $request->all();

            $thumbnailImage = [];
            $assetsDocument = []; 
            $purchaseDocument = [];
            $insuranceDocument = [];

            if ($request->hasfile('p_thumbnail_image')) {
                foreach ($request->file('p_thumbnail_image') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    // Store the file and get the path
                    $filePath = $file->storeAs('public/uploads/assets/thumbnail_image', $name);
                    // Add the path to the array
                    $thumbnailImage[] = $filePath;
                }
            }

            // if ($request->hasfile('p_thumbnail_image')) {
            //     foreach ($request->file('p_thumbnail_image') as $file) {
            //         $name = time() . '_' . $file->getClientOriginalName();
            //         $file->move(public_path('uploads/assets/thumbnail_image'), $name);
            //         $thumbnailImage[] = 'uploads/assets/thumbnail_image/' . $name;
            //     }
            // }

            // $thumbnailImage = [];
            // if ($request->hasfile('p_thumbnail_image')) {
            //     foreach ($request->file('p_thumbnail_image') as $file) {
            //         $image = Image::make($file)->resize(300, 300, function ($constraint) {
            //             $constraint->aspectRatio();
            //             $constraint->upsize();
            //         });
            //         $name = time() . '_' . $file->getClientOriginalName();
            //         $path = 'uploads/assets/thumbnail_image' . $name;
            //         Storage::put($path, (string) $image->encode());
            //         $thumbnailImage[] = $path;
            //     }
            // }
            // $input['p_thumbnail_image'] = $thumbnailImage;


            if ($request->hasfile('p_assets_document')) {
                foreach ($request->file('p_assets_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/assets_document'), $name);
                    $assetsDocument[] = 'uploads/assets/assets_document/' . $name;
                }
            }

            if ($request->hasfile('p_purchase_document')) {
                foreach ($request->file('p_purchase_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/purchase_document'), $name);
                    $purchaseDocument[] = 'uploads/assets/purchase_document/' . $name;
                }
            }

            if ($request->hasfile('p_insurance_document')) {
                foreach ($request->file('p_insurance_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/insurance_document'), $name);
                    $insuranceDocument[] = 'uploads/assets/insurance_document/' . $name;
                }
            }

            $input['p_thumbnail_image'] = $thumbnailImage;
            $input['p_assets_document'] = $assetsDocument;
            $input['p_purchase_document'] = $purchaseDocument;
            $input['p_insurance_document'] = $insuranceDocument;

            $input['p_registered_by'] = Auth::id();

            $currentTime = Carbon::now();
            $input['p_register_date'] = $currentTime;

            // if (isset($data['p_asset_details'])) {
            //     if (is_string($data['p_asset_details'])) {
            //         $decodedItems = json_decode($data['p_asset_details'], true);
            //         if (json_last_error() === JSON_ERROR_NONE) {
            //             $data['p_asset_details'] = $decodedItems;
            //         } else {
            //             throw new \Exception('asset details must be a valid JSON string or array');
            //         }
            //     }
            //     if (!is_array($data['p_asset_details'])) {
            //         throw new \Exception('asset details must be a valid array');
            //     }
            // } else {
            //     $data['p_asset_details'] = [];
            // }

            // foreach ($asset_details as &$detail) {
            //     // Generate the unique URL based on model number and serial number
            //     $uniqueIdentifier = $detail['modelNumber'] . '-' . $detail['serialNumber'];

            //     $qrCodeUrl = "https://nextjs.example.com/assets/{$uniqueIdentifier}";
                    
            //     $qrCodePath = 'qrcodes/' . uniqid() . '.png';
                    
            //     // Generate the QR code with the URL
            //     // QrCode::format('png')->generate($qrCodeUrl, public_path($qrCodePath));
                    
            //     // Store the QR code path in the asset details
            //     $detail['qr_code'] = url($qrCodePath);
            // }

            // Encode the modified asset details array into JSON
            // $input['p_asset_details'] = $asset_details;
            $input['p_deleted'] = false;
            $input['p_deleted_at'] = null;
            $input['p_deleted_by'] = null;

            $this->AssetsManagementService->createAssetRegister($input); 

            return response()->json(['message' => 'assest saved successfully'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit Assest', 'message' => $e->getMessage()], 500);
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
    public function update(Request $request)
    {
        try {
            $input = $request->all();

            $thumbnailImage = [];
            $assetsDocument = []; 
            $purchaseDocument = [];
            $insuranceDocument = [];

            if ($request->hasfile('p_thumbnail_image')) {
                foreach ($request->file('p_thumbnail_image') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/thumbnail_image'), $name);
                    $thumbnailImage[] = 'uploads/assets/thumbnail_image/' . $name;
                }
            }

            if ($request->hasfile('p_assets_document')) {
                foreach ($request->file('p_assets_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/assets_document'), $name);
                    $assetsDocument[] = 'uploads/assets/assets_document/' . $name;
                }
            }

            if ($request->hasfile('p_purchase_document')) {
                foreach ($request->file('p_purchase_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/purchase_document'), $name);
                    $purchaseDocument[] = 'uploads/assets/purchase_document/' . $name;
                }
            }

            if ($request->hasfile('p_insurance_document')) {
                foreach ($request->file('p_insurance_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/assets/insurance_document'), $name);
                    $insuranceDocument[] = 'uploads/assets/insurance_document/' . $name;
                }
            }

            $input['p_thumbnail_image'] = $thumbnailImage;
            $input['p_assets_document'] = $assetsDocument;
            $input['p_purchase_document'] = $purchaseDocument;
            $input['p_insurance_document'] = $insuranceDocument;

            $currentTime = Carbon::now();
            $input['p_updated_at'] = $currentTime;

            $this->AssetsManagementService->updateAsset($input); 

            return response()->json(['message' => 'assest update successfully'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit Assest', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->AssetsManagementService->deleteAsset($id); 

            return response()->json(['message' => 'assest saved successfully'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit Assest', 'message' => $e->getMessage()], 500);
        }
    }
}

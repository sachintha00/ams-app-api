<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MasterEntryService;

class AssetController extends Controller
{
    protected $masterEntryService;

    public function __construct(MasterEntryService $masterEntryService)
    {
        $this->masterEntryService = $masterEntryService;
    }

    public function getAssetTypes(){

        try {
            $results = $this->masterEntryService->getAssetTypes();

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve asset types ' . $e->getMessage()], 500);
        }
        
    }
}

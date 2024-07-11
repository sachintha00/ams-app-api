<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrefixService;

class PrefixController extends Controller
{
    protected $prefixService;

    public function __construct(PrefixService $prefixService)
    {
        $this->prefixService = $prefixService;
    }
    public function getAllPrefixes(Request $request, $process_id)
    {
        try {
            $procurementId = (int)$process_id;
            $results = $this->prefixService->getAllPrefixes($procurementId);

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve prefixes ' . $e->getMessage()], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SidebarService;

class sidebarController extends Controller
{
    protected $SidebarService;

    public function __construct(SidebarService $SidebarService)
    {
        $this->SidebarService = $SidebarService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $userid = $user->id;
            $sidebaritem = $this->SidebarService->getSidebarItem($userid);

            return response()->json([
                'sidebaritem' => $sidebaritem
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

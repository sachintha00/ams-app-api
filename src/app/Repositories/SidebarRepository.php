<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SidebarRepository
{
    public function getSidebarItem($id)
    {
        DB::select('CALL STORE_PROCEDURE_SIDEBAR_WITH_PERMISSION(?)', [$id]);
        $allassesttypeAsArray = DB::table('sidebar_item_from_store_procedure')->select('*')->get();
        
        return $allassesttypeAsArray;
    }
}
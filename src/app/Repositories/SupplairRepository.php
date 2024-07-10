<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SupplairRepository
{
    public function getAllSupplair()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_SUPPLAIR()');
        $allSupplairAsArray = DB::table('supplair_from_store_procedure')->select('*')->get();
        
        return $allSupplairAsArray;
    }
}
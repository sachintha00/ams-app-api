<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MasterEntryRepository
{
    public function getAllAssetsTypes()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_ASSET_TYPES()');
        $allassesttypeAsArray = DB::table('assest_type_from_store_procedure')->select('*')->get();
        
        return $allassesttypeAsArray;
    }

    public function getAllItemTypes()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_ITEM_TYPES()');
        $allitemtypeAsArray = DB::table('item_type_from_store_procedure')->select('*')->get();
        
        return $allitemtypeAsArray; 
    }

    public function getAllPeriodTypes()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_PERIOD_TYPES()');
        $allPeriodtypeAsArray = DB::table('period_type_from_store_procedure')->select('*')->get();
        
        return $allPeriodtypeAsArray; 
    }

    public function getAllAvailabilityTypes()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_AVAILABILITY_TYPES()');
        $allAvailabilitytypeAsArray = DB::table('availability_type_from_store_procedure')->select('*')->get();
        
        return $allAvailabilitytypeAsArray; 
    }

    public function getAllPriorityTypes()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_PRIORITY_TYPES()');
        $allprioritytypeAsArray = DB::table('priority_type_from_store_procedure')->select('*')->get();
        
        return $allprioritytypeAsArray; 
    }


    public function getAssetTypes()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_ASSET_TYPES()');
        $assetTypesArray = DB::table('asset_types_from_store_procedure')->select('*')->get();
        
        return $assetTypesArray; 
    }

    public function getAllAssetCategories()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_ASSEST_CATEGORIES()');
        $assetCategories = DB::table('asset_categories_from_store_procedure')->select('*')->get();
        
        return $assetCategories; 
    }
}

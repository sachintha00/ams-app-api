<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PrefixRepository
{    public function getAllPrefixes($process_id=0)
    {
        try {
            DB::select('CALL STORE_PROCEDURE_GET_PREFIXES_RELATED_PROCESS(?, ?)', [$process_id, NULL]);

            $prefixesData = DB::table('prefix_list_with_next_number_from_store_procedure')->select('*')->get();
            $prefixes = $prefixesData->map(function ($item) {
                return json_decode($item->prefixes_data, true);
            });

            return $prefixes[0];
    
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            
        } catch (\Exception $e) {
            // Catch all other exceptions
            return $e->getMessage();
        }
    }
}
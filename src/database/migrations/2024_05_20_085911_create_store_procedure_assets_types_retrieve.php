<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            "CREATE OR REPLACE PROCEDURE STORE_PROCEDURE_RETRIEVE_ASSET_TYPES( 
                IN p_asset_type_id INT DEFAULT NULL
            )
            AS $$
            BEGIN
                DROP TABLE IF EXISTS asset_type_from_store_procedure;
            
                IF p_asset_type_id IS NOT NULL AND p_asset_type_id <= 0 THEN
                    RAISE EXCEPTION 'Invalid p_asset_type_id: %', p_asset_type_id;
                END IF;
            
                CREATE TEMP TABLE asset_type_from_store_procedure AS
                SELECT
                    a.id AS asset_type_id,
                    a.name,
                    a.description,
                    a.created_at,
                    a.updated_at
                FROM
                    assets_Types a
                WHERE
                    (a.id = p_asset_type_id OR p_asset_type_id IS NULL OR p_asset_type_id = 0)
                    AND a.deleted_at IS NULL
                    AND a.isActive = TRUE;
            END;
            $$ LANGUAGE plpgsql;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS STORE_PROCEDURE_RETRIEVE_ASSET_TYPES');
    }
};
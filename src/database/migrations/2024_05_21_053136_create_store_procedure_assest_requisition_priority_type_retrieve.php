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
        DB::unprepared(<<<SQL
            CREATE OR REPLACE PROCEDURE STORE_PROCEDURE_RETRIEVE_PRIORITY_TYPES( 
                IN p_priority_type_id INT DEFAULT NULL
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                DROP TABLE IF EXISTS priority_type_from_store_procedure;
            
                IF p_priority_type_id IS NOT NULL AND p_priority_type_id <= 0 THEN
                    RAISE EXCEPTION 'Invalid p_priority_type_id: %', p_priority_type_id;
                END IF;
            
                CREATE TEMP TABLE priority_type_from_store_procedure AS
                SELECT
                    arprt.id AS priority_type_id,
                    arprt.name,
                    arprt.description,
                    arprt.created_at,
                    arprt.updated_at
                FROM
                    asset_requisition_priority_types arprt
                WHERE
                    (arprt.id = p_priority_type_id OR p_priority_type_id IS NULL OR p_priority_type_id = 0)
                    AND arprt.deleted_at IS NULL
                    AND arprt."isActive" = TRUE;
            END;
            $$;
            SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS STORE_PROCEDURE_RETRIEVE_PRIORITY_TYPES');
    }
};
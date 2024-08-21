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
            "CREATE OR REPLACE PROCEDURE STORE_PROCEDURE_RETRIEVE_SUPPLIER (
                IN p_supplier_id INT DEFAULT NULL
            )
            AS $$
            BEGIN
                DROP TABLE IF EXISTS supplier_from_store_procedure;
            
                IF p_supplier_id IS NOT NULL AND p_supplier_id <= 0 THEN
                    RAISE EXCEPTION 'Invalid p_supplier_id: %', p_supplier_id;
                END IF;
            
                CREATE TEMP TABLE supplier_from_store_procedure AS
                SELECT
                    s.id AS supplier_id,
                    s.name,
                    s.contact_no,
                    s.address,
                    s.description,
                    s.created_at,
                    s.updated_at
                FROM
                    supplier s
                WHERE
                    (s.id = p_supplier_id OR p_supplier_id IS NULL OR p_supplier_id = 0)
                    AND s.deleted_at IS NULL
                    AND s.isActive = TRUE;
            END;
            $$ LANGUAGE plpgsql;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS STORE_PROCEDURE_RETRIEVE_SUPPLIER');
    }
};
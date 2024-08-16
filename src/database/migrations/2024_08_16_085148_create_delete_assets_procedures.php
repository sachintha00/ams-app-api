<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared(
            'CREATE OR REPLACE PROCEDURE STORE_PROCEDURE_REMOVE_ASSETS(
                IN _asset_id bigint,
                IN p_deleted BOOLEAN,
                IN p_register_date TIMESTAMP,
                IN p_registered_by BIGINT
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                UPDATE assets
                SET 
                    deleted = p_deleted,
                    deleted_at = p_register_date,
                    deleted_at = p_registered_by
                WHERE id = _asset_id;
                COMMIT;
            END; 
            $$;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS update_asset_requisition_status;');
    }
};

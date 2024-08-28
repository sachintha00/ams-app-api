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
            CREATE OR REPLACE PROCEDURE STORE_PROCEDURE_GET_ALL_USER_ASSET_REQUISITIONS( 
                IN p_user_id INT DEFAULT NULL
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                DROP TABLE IF EXISTS get_all_user_asset_requisitions_from_store_procedure;
            
                IF p_user_id IS NOT NULL AND p_user_id <= 0 THEN
                    RAISE EXCEPTION 'Invalid p_user_id: %', p_user_id;
                END IF;
            
                CREATE TEMP TABLE get_all_user_asset_requisitions_from_store_procedure AS
                SELECT
                    ar.id AS asset_requisitions_id,
                    ar.requisition_id,
                    ar.requisition_by,
                    ar.requisition_date,
                    ar.requisition_status,
                    COALESCE(
                        json_agg(
                            json_build_object(
                                'item_id', ari.id,
                                'item_name', ari.item_name,
                                'asset_type', ari.asset_type,
                                'quantity', ari.quantity,
                                'budget', ari.budget,
                                'business_purpose', ari.business_purpose,
                                'upgrade_or_new', ari.upgrade_or_new,
                                'period_status', ari.period_status,
                                'period_from', ari.period_from,
                                'period_to', ari.period_to,
                                'period', ari.period,
                                'availability_type', ari.availability_type,
                                'priority', ari.priority,
                                'required_date', ari.required_date,
                                'organization', ari.organization,
                                'reason', ari.reason,
                                'business_impact', ari.business_impact,
                                'suppliers', ari.suppliers,
                                'files', ari.files,
                                'item_details', ari.item_details,
                                'expected_conditions', ari.expected_conditions,
                                'maintenance_kpi', ari.maintenance_kpi,
                                'service_support_kpi', ari.service_support_kpi,
                                'consumables_kpi', ari.consumables_kpi
                            )
                        ) FILTER (WHERE ari.id IS NOT NULL), '[]'
                    ) AS items
                FROM
                    users u
                INNER JOIN
                    asset_requisitions ar ON u.id = ar.requisition_by
                LEFT JOIN
                    asset_requisitions_items ari ON ar.id = ari.asset_requisition_id
                WHERE
                    (u.id = p_user_id OR p_user_id IS NULL OR p_user_id = 0)
                    AND u.deleted_at IS NULL
                    AND u."isActive" = TRUE
                GROUP BY
                    ar.id;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS STORE_PROCEDURE_GET_ALL_USER_ASSET_REQUISITIONS');
    }
};
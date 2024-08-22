<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $procedure = <<<SQL
                CREATE OR REPLACE PROCEDURE STORE_PROCEDURE_SIDEBAR_WITH_PERMISSION( 
                    IN p_user_id INT DEFAULT NULL
                )
                LANGUAGE plpgsql
                AS $$
                BEGIN
                    DROP TABLE IF EXISTS sidebar_item_from_store_procedure;
                
                    IF p_user_id IS NOT NULL AND p_user_id <= 0 THEN
                        RAISE EXCEPTION 'Invalid p_user_id: %', p_user_id;
                    END IF;
                
                    CREATE TEMP TABLE sidebar_item_from_store_procedure AS
                    SELECT
                        tbl.id,
                        tbl.permission_id,
                        tbl.parent_id,
                        tbl.menuname,
                        tbl.menulink,
                        tbl.icon
                    FROM
                        users u
                    INNER JOIN
                        model_has_roles mhr ON u.id = mhr.model_id
                    INNER JOIN
                        roles r ON mhr.role_id = r.id
                    INNER JOIN
                        role_has_permissions rhp ON r.id = rhp.role_id
                    INNER JOIN
                        permissions p ON rhp.permission_id = p.id
                    INNER JOIN
                        tbl_menu tbl ON tbl.permission_id = p.id
                    WHERE
                        (u.id = p_user_id OR p_user_id IS NULL OR p_user_id = 0)
                        AND u.deleted_at IS NULL
                        AND u."isActive" = TRUE
                    GROUP BY
                    tbl.id, tbl.permission_id, tbl.parent_id, tbl.menuname, tbl.menulink, tbl.icon;
                END;
                \$\$;
                SQL;
                
            // Execute the SQL statement
            DB::unprepared($procedure);
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS STORE_PROCEDURE_SIDEBAR_WITH_PERMISSION');
    }
};
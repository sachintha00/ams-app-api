<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_requisitions_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_requisition_id')->constrained('asset_requisitions')->onDelete('cascade');
            $table->string('item_name', 255);
            $table->string('asset_type', 255);
            $table->integer('quantity');
            $table->string('budget')->nullable();
            $table->string('business_purpose', 255);
            $table->string('upgrade_or_new', 255);
            $table->string('period_status', 255);
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->string('period', 255)->nullable();
            $table->string('availability_type', 255);
            $table->string('priority', 255);
            $table->date('required_date');
            $table->unsignedBigInteger('organization');
            $table->foreign('organization')->references('id')->on('organization')->onDelete('cascade');
            $table->string('reason', 255);
            $table->string('business_impact', 255);
            $table->json('suppliers')->nullable();
            $table->json('files')->nullable();
            $table->json('item_details')->nullable();
            $table->json('expected_conditions')->nullable();
            $table->json('maintenance_kpi')->nullable();
            $table->json('service_support_kpi')->nullable();
            $table->json('consumables_kpi')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_requisitions_items');
    }
};
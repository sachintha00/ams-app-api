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
        Schema::create('tbl_menu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('tbl_menu')->onDelete('cascade');
            $table->Integer('RightsCode')->nullable();
            $table->string('MenuTxtCode')->nullable();
            $table->string('MenuName')->nullable();
            $table->string('Description')->nullable();
            $table->string('path')->nullable();
            $table->string('MenuLink')->nullable();
            $table->Integer('MenuOrder')->nullable();
            $table->boolean('Enabled')->nullable()->default(0);
            $table->string('MenuPath')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_menu');
    }
};

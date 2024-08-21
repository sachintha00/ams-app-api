<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_behavior_types', function (Blueprint $table) {
            $table->id();
            $table->string('workflow_behavior_type');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('isActive')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_behavior_types');
    }
};
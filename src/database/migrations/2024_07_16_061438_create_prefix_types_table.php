<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('prefix_types', function (Blueprint $table) {
            $table->id();
            $table->string('prefix_type_name', 255);
            $table->text('description')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prefix_types');
    }
};
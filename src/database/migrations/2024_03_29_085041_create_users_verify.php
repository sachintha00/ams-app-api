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
        Schema::create('users_verify', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('headertoken');
            $table->string('token');
            $table->timestamp('expiry_date');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->boolean('isActive')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_verify');
    }
};
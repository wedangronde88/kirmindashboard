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
        Schema::create('trip_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->foreignId('safety_check_id')->nullable()->constrained()->onDelete('set null');
            $table->date('trip_date');
            $table->string('driver')->nullable();
            $table->string('truck_brand')->nullable();
            $table->string('truck_plate')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('driver_fee', 15, 2)->nullable();
            $table->decimal('gas_price', 15, 2)->nullable();
            $table->decimal('highway_price', 15, 2)->nullable();
            $table->decimal('crossing_ferry_and_other_cost', 15, 2)->nullable();
            $table->decimal('incentive', 15, 2)->nullable();
            $table->decimal('helper_fee', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_histories');
    }
};
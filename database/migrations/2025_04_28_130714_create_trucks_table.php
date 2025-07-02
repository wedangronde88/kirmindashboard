<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->string('plat_no')->unique();
            $table->string('brand_truk');
            $table->string('no_stnk');
            $table->string('no_kir');
            $table->string('no_pajak');
            $table->timestamps();
            $table->string('image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
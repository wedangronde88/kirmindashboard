<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_trip_date_to_safety_checks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('safety_checks', function (Blueprint $table) {
            $table->date('trip_date')->nullable()->after('destination');
        });
    }

    public function down(): void
    {
        Schema::table('safety_checks', function (Blueprint $table) {
            $table->dropColumn('trip_date');
        });
    }
};

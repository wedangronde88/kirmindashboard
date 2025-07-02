<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('trip_histories', function (Blueprint $table) {
            // Example: add new columns
            $table->date('tanggal_lunas')->nullable();
            $table->string('status')->nullable();
            $table->string('sales')->nullable();
            $table->string('clientele')->nullable();
            $table->string('document')->nullable();
            $table->boolean('printed')->nullable();
            $table->string('no_invoice')->nullable();
            $table->string('payment_voucher')->nullable();
            $table->string('journal')->nullable();
            $table->string('struk')->nullable();
            // ...add/remove/rename columns as needed
        });
    }

    public function down()
    {
        Schema::table('trip_histories', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_lunas', 'status', 'sales', 'clientele', 'document', 'printed',
                'no_invoice', 'payment_voucher', 'journal', 'struk'
            ]);
        });
    }
};
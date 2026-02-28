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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('etims_status')->nullable()->after('status');
            $table->string('etims_receipt_no')->nullable()->after('etims_status');
            $table->text('etims_qr_code')->nullable()->after('etims_receipt_no');
            $table->json('etims_response')->nullable()->after('etims_qr_code');
            $table->timestamp('etims_submitted_at')->nullable()->after('etims_response');
            $table->timestamp('etims_last_checked_at')->nullable()->after('etims_submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'etims_status',
                'etims_receipt_no',
                'etims_qr_code',
                'etims_response',
                'etims_submitted_at',
                'etims_last_checked_at',
            ]);
        });
    }
};

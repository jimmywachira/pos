<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('shifts', 'status')) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->enum('status', ['active', 'completed'])->default('active');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shifts', 'status')) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            });
        }
    }
};
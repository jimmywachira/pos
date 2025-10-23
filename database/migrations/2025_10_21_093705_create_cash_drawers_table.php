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
        Schema::table('shifts', function (Blueprint $table) {
            // Add the foreign key after the 'user_id' column for organization
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // Drop the foreign key constraint before dropping the column
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};


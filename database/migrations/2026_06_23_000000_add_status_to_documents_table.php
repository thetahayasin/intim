<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('status', ['draft', 'final'])->default('draft')->after('signed_pdf');
        });

        // Existing agreements keep their current behaviour (signature shown, no watermark).
        DB::table('documents')->update(['status' => 'final']);
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

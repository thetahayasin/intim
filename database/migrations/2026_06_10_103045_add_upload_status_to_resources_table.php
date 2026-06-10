<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('original_filename'); // associate id
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved')->after('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['uploaded_by', 'status']);
        });
    }
};

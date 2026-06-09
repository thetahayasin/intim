<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // First, drop the existing foreign keys
            $table->dropForeign(['client_id']);
            $table->dropForeign(['billing_id']);

            // Then, re-add them with onDelete('cascade')
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('billing_id')->references('id')->on('billings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Rollback changes by dropping the new foreign keys
            $table->dropForeign(['client_id']);
            $table->dropForeign(['billing_id']);

            // Re-add the original foreign keys without cascade
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('billing_id')->references('id')->on('billings');
        });
    }
};

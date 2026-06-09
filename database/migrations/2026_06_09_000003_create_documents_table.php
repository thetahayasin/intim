<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['proposal', 'agreement']);
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_name');
            $table->tinyInteger('firm')->default(0); // 0 = Asif Associates, 1 = HAMD
            $table->json('services')->nullable();    // [{service, fee}]
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

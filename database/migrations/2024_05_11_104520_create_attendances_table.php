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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('associate_id');
            $table->foreign('associate_id')->references('id')->on('associates')->onDelete('cascade');
            $table->date('date');
            $table->boolean('is_leave')->default(false);
            $table->string('reason_for_leave')->nullable();
            $table->boolean('leave_approval')->default(false)->nullable();
            $table->boolean('is_present')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('work_hours')->nullable();
            $table->text('work_done')->nullable();
            $table->timestamps();
        
            // Unique constraint for date and associate_id combination
            $table->unique(['date', 'associate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

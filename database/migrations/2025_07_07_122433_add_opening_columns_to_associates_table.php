<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('associates', function (Blueprint $table) {
            $table->integer('opening_presents')->default(0);
            $table->integer('opening_leaves')->default(0);
            $table->integer('opening_absents')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('associates', function (Blueprint $table) {
            $table->dropColumn(['opening_presents', 'opening_leaves', 'opening_absents']);
        });
    }
};

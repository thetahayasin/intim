<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $defaults = [
            'smtp_host'         => env('MAIL_HOST', ''),
            'smtp_port'         => env('MAIL_PORT', '587'),
            'smtp_username'     => env('MAIL_USERNAME', ''),
            'smtp_password'     => env('MAIL_PASSWORD', ''),
            'smtp_encryption'   => env('MAIL_ENCRYPTION', 'tls'),
            'smtp_from_name'    => env('MAIL_FROM_NAME', 'Asif Associates'),
            'smtp_from_address' => env('MAIL_FROM_ADDRESS', ''),
            'site_logo'         => null,
            'site_favicon'      => null,
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->insert([
                'key'        => $key,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

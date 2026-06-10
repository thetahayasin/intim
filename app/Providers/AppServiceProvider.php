<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $smtp = \Illuminate\Support\Facades\DB::table('settings')
                    ->whereIn('key', ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_name', 'smtp_from_address'])
                    ->pluck('value', 'key')
                    ->all();

                if (!empty($smtp['smtp_host'])) {
                    config([
                        'mail.mailers.smtp.host'       => $smtp['smtp_host'],
                        'mail.mailers.smtp.port'       => $smtp['smtp_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.username'   => $smtp['smtp_username'] ?? null,
                        'mail.mailers.smtp.password'   => $smtp['smtp_password'] ?? null,
                        'mail.mailers.smtp.encryption' => $smtp['smtp_encryption'] ?? 'tls',
                        'mail.from.name'               => $smtp['smtp_from_name'] ?? config('mail.from.name'),
                        'mail.from.address'            => $smtp['smtp_from_address'] ?? config('mail.from.address'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Silently fail during setup / before migrations run
        }
    }
}

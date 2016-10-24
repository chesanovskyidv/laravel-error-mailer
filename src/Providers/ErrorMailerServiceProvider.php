<?php

namespace BwtTeam\LaravelErrorMailer\Providers;

use Illuminate\Support\ServiceProvider;

class ErrorMailerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../../config/error-mailer.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('error-mailer.php');
        } else {
            $publishPath = base_path('config/error-mailer.php');
        }
        $this->publishes([$configPath => $publishPath], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

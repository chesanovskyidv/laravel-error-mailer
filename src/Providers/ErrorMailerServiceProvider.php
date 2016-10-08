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
        $this->publishes([__DIR__ . '/../../config/error-mailer.php' => config_path() . "/error-mailer.php"], 'config');
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

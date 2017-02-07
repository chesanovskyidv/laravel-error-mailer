<?php

namespace BwtTeam\LaravelErrorMailer\Providers;

use BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\Writer;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

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
        $this->configureMailHandlers($this->app, $this->app->make('log'));
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Log\Writer $log
     *
     * @return void
     */
    protected function configureMailHandlers(Application $app, Writer $log)
    {
        $config = $app->make('config');

        if ($config->get('error-mailer.enabled', false)) {
            $this->configureSingleMailHandler($app, $log->getMonolog());
        }
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param \Monolog\Logger $monolog
     *
     * @return mixed
     */
    protected function configureSingleMailHandler(Application $app, Logger $monolog)
    {
        $config = $app->make('config');

        $mail = $config->get('error-mailer.mail', []);
        $from = $config->get('mail.from');
        $processors = $config->get('error-mailer.processors', []);
        $logLevel = $config->get('error-mailer.log_level', Logger::ERROR);

        $configurator = new MailConfigurator($mail['subject'], $mail['to'], $from, $processors, $logLevel);

        return call_user_func($configurator, $monolog);
    }
}

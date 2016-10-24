<?php

namespace BwtTeam\LaravelErrorMailer;

use BwtTeam\LaravelErrorMailer\Configurators\MailConfigurator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\Writer;
use Monolog\Logger;

class ConfigureLogging extends \Illuminate\Foundation\Bootstrap\ConfigureLogging
{
    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Log\Writer $log
     * @return void
     */
    protected function configureHandlers(Application $app, Writer $log)
    {
        parent::configureHandlers($app, $log);
        $this->configureMailHandlers($app, $log);
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  \Illuminate\Log\Writer $log
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
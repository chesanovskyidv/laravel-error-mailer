<?php

namespace BwtTeam\LaravelErrorMailer;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\Writer;
use Illuminate\Mail\TransportManager;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\SwiftMailerHandler;
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
     * @return void
     */
    protected function configureSingleMailHandler(Application $app, Logger $monolog)
    {
        $config = $app->make('config');

        $mail = $config->get('error-mailer.mail', []);
        $from = $config->get('mail.from');
        $processors = $config->get('error-mailer.processors', []);

        $transportManager = new TransportManager($app);
        $mailer = new \Swift_Mailer($transportManager->driver());
        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance()
            ->setSubject($mail['subject'])
            ->setTo($mail['to'])
            ->setContentType('text/html');

        if (is_array($from) && isset($from['address'])) {
            $message->setFrom($from['address'], $from['name']);
        }

        $mailHandler = new SwiftMailerHandler($mailer, $message);
        $mailHandler->setFormatter(new HtmlFormatter());
        foreach ($processors as $processor) {
            $processor = new $processor;
            if (method_exists($processor, 'register')) {
                $processor->register($app);
            }
            $mailHandler->pushProcessor($processor);
        }

        $handler = new DeduplicationHandler($mailHandler);

        $monolog->pushHandler($handler);
    }
}
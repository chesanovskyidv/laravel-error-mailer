<?php

namespace BwtTeam\LaravelErrorMailer\Configurators;

use Illuminate\Mail\TransportManager;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;

class FileConfigurator extends BaseConfigurator
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;
    /** @var string */
    protected $file;
    /** @var int */
    protected $logLevel;

    /**
     * FileConfigurator constructor.
     *
     * @param string $file
     * @param int $logLevel
     */
    public function __construct($file = null, $logLevel = Logger::DEBUG)
    {
        $this->setApp(app());
        if (empty($file)) {
            $file = storage_path($this->isLumen() ? 'logs/lumen.log' : 'logs/laravel.log');
        }
        $this->setFile($file);
        $this->setLogLevel($logLevel);
    }

    /**
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     * @return $this
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @param int $logLevel
     * @return $this
     */
    public function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;

        return $this;
    }

    /**
     * @param Logger $logger
     * @return Logger
     */
    public function configure(\Monolog\Logger $logger)
    {
        $fileHandler = $this->createFileHandler($this->getFile(), $this->getProcessors(), $this->getLogLevel());
        $logger->pushHandler($fileHandler);

        return $logger;
    }

    /**
     * @param $file
     * @param array $processors
     * @param int $logLevel
     * @return StreamHandler
     */
    protected function createFileHandler($file, $processors = [], $logLevel = Logger::ERROR)
    {
        $fileHandler = (new StreamHandler($file, $logLevel))
            ->setFormatter(new LineFormatter(null, null, true, true));
        foreach ($processors as $processor) {
            $fileHandler->pushProcessor($processor);
        }

        return $fileHandler;
    }
}
<?php

namespace BwtTeam\LaravelErrorMailer\Configurators;

use Illuminate\Mail\TransportManager;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Logger;

class MailConfigurator extends BaseConfigurator
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;
    /** @var string */
    protected $subject;
    /** @var string */
    protected $to;
    /** @var string */
    protected $from;
    /** @var int */
    protected $logLevel;
    /** @var \Swift_Transport */
    protected $sendmailTransport = null;

    /**
     * MailConfigurator constructor.
     *
     * @param string $subject
     * @param string $to
     * @param string $from
     * @param callable[] $processors
     * @param int $logLevel
     * @param \Swift_Transport $sendmailTransport
     */
    public function __construct($subject, $to, $from, array $processors = [], $logLevel = Logger::ERROR, \Swift_Transport $sendmailTransport = null)
    {
        $this->setApp(app());
        $this->setSubject($subject);
        $this->setTo($to);
        $this->setFrom($from);
        $this->addProcessors($processors);
        $this->setLogLevel($logLevel);
        if (!is_null($sendmailTransport)) {
            $this->setSendmailTransport($sendmailTransport);
        }
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
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
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
     * @return \Swift_Transport
     */
    public function getSendmailTransport()
    {
        return $this->sendmailTransport;
    }

    /**
     * @param \Swift_Transport $sendmailTransport
     */
    public function setSendmailTransport(\Swift_Transport $sendmailTransport)
    {
        $this->sendmailTransport = $sendmailTransport;
    }

    /**
     * @param Logger $logger
     * @return Logger
     */
    public function configure(\Monolog\Logger $logger)
    {
        $mailer = new \Swift_Mailer($this->createMailDriver());

        $message = $this->createMessage($this->getSubject(), $this->getTo(), $this->getFrom());
        $mailHandler = $this->createMailHandler($mailer, $message, $this->getProcessors(), $this->getLogLevel());
        $handler = new DeduplicationHandler($mailHandler);

        $logger->pushHandler($handler);

        return $logger;
    }

    /**
     * @return mixed|\Swift_Transport
     */
    protected function createMailDriver()
    {
        if (is_null($this->getSendmailTransport())) {
            $transportManager = new TransportManager($this->getApp());
            return $transportManager->driver();
        }

        return $this->getSendmailTransport();
    }

    /**
     * @param string $subject
     * @param string $to
     * @param string $from
     * @return \Swift_Message
     */
    protected function createMessage($subject, $to, $from)
    {
        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setTo($to)
            ->setContentType('text/html');

        if (is_array($from) && isset($from['address'])) {
            $message->setFrom($from['address'], $from['name']);
        } else {
            $message->setFrom($from);
        }

        return $message;
    }

    /**
     * @param \Swift_Mailer $mailer
     * @param $message
     * @param callable[] $processors
     * @param int $logLevel
     * @return SwiftMailerHandler
     */
    protected function createMailHandler(\Swift_Mailer $mailer, $message, $processors = [], $logLevel = Logger::ERROR)
    {
        $mailHandler = new SwiftMailerHandler($mailer, $message, $logLevel);
        $mailHandler->setFormatter(new HtmlFormatter());
        foreach ($processors as $processor) {
            $mailHandler->pushProcessor($processor);
        }

        return $mailHandler;
    }
}
<?php

namespace BwtTeam\LaravelErrorMailer\Configurators;

use Monolog\Logger;

abstract class BaseConfigurator
{
    /** @var callable[] array */
    protected $configurators = [];
    /** @var array */
    protected $processors = [];

    /**
     * @param Logger $logger
     * @return Logger
     */
    public function __invoke(\Monolog\Logger $logger)
    {
        $this->configure($logger);
        foreach ($this->configurators as $configurator) {
            call_user_func($configurator, $logger);
        }

        return $logger;
    }

    /**
     * @return array
     */
    public function getProcessors()
    {
        return $this->processors;
    }

    /**
     * @param callable|callable[] $processors
     * @return $this
     */
    public function addProcessors($processors)
    {
        $processors = is_array($processors) ? $processors : func_get_args();

        foreach ($processors as $processor) {
            $processor = is_string($processor) ? new $processor : $processor;

            if (!is_callable($processor)) {
                throw new \InvalidArgumentException('Processors must be valid callables (callback or object with an __invoke method), ' . var_export($processor, true) . ' given');
            }
            if (method_exists($processor, 'register')) {
                $processor->register($this->getApp());
            }

            $this->processors[] = $processor;
        }

        return $this;
    }


    /**
     * @param Logger $logger
     * @return Logger
     */
    abstract public function configure(\Monolog\Logger $logger);

    /**
     * @param callable $configurator
     * @return $this
     */
    public function with(callable $configurator)
    {
        $this->configurators[] = $configurator;

        return $this;
    }

    /**
     * @return bool
     */
    protected function isLumen()
    {
        return is_a(\app(), 'Laravel\Lumen\Application');
    }
}
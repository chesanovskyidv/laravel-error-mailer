<?php

namespace BwtTeam\LaravelErrorMailer\Configurators;

use Monolog\Logger;

abstract class BaseConfigurator
{
    /** @var callable[] array */
    protected $configurators = [];

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
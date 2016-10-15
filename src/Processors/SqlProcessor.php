<?php

namespace BwtTeam\LaravelErrorMailer\Processors;

use BwtTeam\LaravelErrorMailer\Providers\SqlListenersServiceProvider;
use \Illuminate\Contracts\Container\Container;

class SqlProcessor
{
    /**
     * @var array|\ArrayAccess
     */
    protected $sql = [];

    /**
     * SqlProcessor constructor.
     */
    public function __construct()
    {
        $listenerClosure = function ($query) {
            $this->listener($query);
        };
        $listenerClosure->bindTo($this);
        SqlListenersServiceProvider::addListener($listenerClosure);
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra']['sql'] = $this->getSql();

        return $record;
    }

    /**
     * @param $query
     */
    public function listener($query)
    {
        $queryStr = vsprintf(str_replace(['%', '?'], ['%%', "'%s'"], $query->sql), $query->bindings) . ';';
        $this->addSql($queryStr, $query->time);
    }

    /**
     * @param string $query
     * @param string $time
     */
    public function addSql($query, $time)
    {
        $this->sql[] = [
            'query' => $query,
            'time' => $time,
        ];
    }

    /**
     * @return array|\ArrayAccess
     */
    protected function getSql()
    {
        return $this->sql;
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function register(Container $app)
    {
        $app->register(SqlListenersServiceProvider::class);
    }
}
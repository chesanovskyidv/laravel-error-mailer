<?php

namespace BwtTeam\LaravelErrorMailer\Providers;

use Illuminate\Support\ServiceProvider;

class SqlListenersServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected static $listeners = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $connection = $this->app->make('db.connection');
        foreach (self::$listeners as $listener) {
            $connection->listen($listener);
        }
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

    /**
     * @param \Closure $callback
     */
    public static function addListener(\Closure $callback)
    {
        self::$listeners[] = $callback;
    }
}

<?php namespace Teepluss\Fast;

use Illuminate\Support\ServiceProvider;

class FastServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('teepluss/fast');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register core fast.
        $this->registerFast();

        // Register commands.
        $this->registerFlushCommand();
        $this->registerForgetCommand();

        // Assign commands.
        $this->commands(
            'fast.forget',
            'fast.flush'
        );
    }

    /**
     * Register fast provider.
     *
     * @return void
     */
    protected function registerFast()
    {
        $this->app['fast'] = $this->app->share(function($app)
        {
            return new Fast($app['config'], $app['cache'], $app['request']);
        });
    }

    /**
     * Register forget cache command.
     *
     * @return void
     */
    protected function registerForgetCommand()
    {
        $this->app['fast.forget'] = $this->app->share(function($app)
        {
            return new Commands\FastForgetCommand($app['fast']);
        });
    }

    /**
     * Register flush cache command.
     *
     * @return void
     */
    protected function registerFlushCommand()
    {
        $this->app['fast.flush'] = $this->app->share(function($app)
        {
            return new Commands\FastFlushCommand($app['fast']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('fast');
    }

}

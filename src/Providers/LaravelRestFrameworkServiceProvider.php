<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; LaravelRestFrameworkServiceProvider
 * Date: May 04, 2020
 * Time: 01:03:38 PM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelRestFramework\Routing\ResourceRegistrar;

class LaravelRestFrameworkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResources();


    }

    /**
     * Override the Route:resouorce() to [list, retrieve, create, update, destroy] instead of 
     * laravle's default resource methods [index, show, store, create, edit, update, delete]
     */
    public function registerResources()
    {
        $registrar = new ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });
    }
}

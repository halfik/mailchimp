<?php

namespace halfik\MailChimp;

use Illuminate\Support\ServiceProvider;

/**
 * Class MailChimpProvider
 * @package MailChimp
 */
class MailChimpProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('mailchimp.php'),
        ], 'config');

        $this->bootBindings();
    }

    /**
     * Bind some Interfaces and implementations.
     */
    protected function bootBindings()
    {
        $this->app->singleton(\MailChimp\MailChimp::class, function ($app) {
            return $app['halfik.mailchimp'];
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/mailchimp.php';
        $this->mergeConfigFrom($configPath, 'mailchimp');
    }


}

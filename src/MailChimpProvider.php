<?php

namespace Halfik\MailChimp;


/**
 * Class MailChimpProvider
 * @package MailChimp
 */
class MailChimpProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/mailchimp.php';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(self::CONFIG_PATH, 'mailchimp');

        $this->app->singleton(MailChimp::class, function ($app) {
            return new MailChimp();
        });

        $this->app->alias(MailChimp::class, 'mailchimp');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([self::CONFIG_PATH => config_path('mailchimp.php')], 'config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [MailChimp::class];
    }

}

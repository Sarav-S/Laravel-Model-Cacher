<?php

namespace Sarav\Providers;

use Sarav\Observers\EventObserver;
use Illuminate\Support\ServiceProvider;

class ModelCacherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $config = $this->app['config'];

        if (count($config['cacheable.models'])) {
            foreach($config['cacheable.models'] as $model) {
                $model::observe(EventObserver::class);
            }
        }

         $this->publishes([
            __DIR__.'/../config/cacheable.php' => config_path('cacheable.php')
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

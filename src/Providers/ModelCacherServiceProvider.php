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

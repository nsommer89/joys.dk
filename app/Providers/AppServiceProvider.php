<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            $configs = \Illuminate\Support\Facades\Cache::rememberForever('joys_configs_all', function () {
                if (\Illuminate\Support\Facades\Schema::hasTable('joys_configs')) {
                    return \App\Models\JoysConfig::all()->pluck('value', 'key')->toArray();
                }

                return [];
            });

            foreach ($configs as $key => $value) {
                config()->set('joys.'.$key, $value);
            }
        } catch (\Exception $e) {
            // Setup phase - ignore
        }
    }
}

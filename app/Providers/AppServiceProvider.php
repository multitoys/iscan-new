<?php

namespace App\Providers;

use App\Models\Sms;
use App\Observers\SmsObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sms::observe(SmsObserver::class);
    }
}

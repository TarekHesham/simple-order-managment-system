<?php

namespace App\Providers;

use App\Models\Product\Receive;
use App\Observers\ReceiveObserver;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // Observer
        Receive::observe(ReceiveObserver::class);
    }
}

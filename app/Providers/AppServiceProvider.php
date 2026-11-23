<?php

namespace App\Providers;

use App\Models\Medicine;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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

        View::composer('partials.header', function ($view) {
            $view->with('lowStock', Medicine::where('stock', '<=', Medicine::LOW_STOCK)->orderBy('stock')->orderBy('name')->get());
        });
    }
}

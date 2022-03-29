<?php

namespace App\Providers;


use App\Models\arena;
use App\Models\import;
use Laravel\Passport\Passport;
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
        view()->composer('layouts.components.sidebar', function ($view) {

            // Get the $data
           $arenaCount =  arena::count();
           $importCount = import::whereNull('status')->count();
            $view->with('arenaCount',$arenaCount)
                 ->with('importCount',$importCount);
        
        });
    }
}

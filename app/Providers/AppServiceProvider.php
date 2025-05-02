<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Guru;
use App\Siswa;
use App\Observers\UserImageObserver;
use App\Observers\SiswaImageObserver;

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
        Schema::defaultStringLength(191);
        
        // Register observers untuk sync image dari guru dan siswa ke user
        Guru::observe(UserImageObserver::class);
        Siswa::observe(SiswaImageObserver::class);
    }
}

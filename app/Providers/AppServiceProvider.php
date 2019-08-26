<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Http\ViewComposers\SettingsComposer;
use Illuminate\Support\Facades\View;

use App\Task;
use App\Observers\TaskObserver;

use App\Order;
use App\Observers\OrderObserver;

use App\User;
use App\Observers\UserObserver;

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

        View::composer(['layouts.app'], SettingsComposer::class);

        Task::observe(TaskObserver::class);
        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);


    }
}

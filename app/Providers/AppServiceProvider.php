<?php

namespace App\Providers;

use App\Core\Application\Repositories\IBookingsRepository;
use App\Core\Application\Repositories\IEventRepository;
use App\Core\Application\Repositories\ITimeOffRepository;
use App\Core\Application\Repositories\IWorkingDaysRepository;
use App\Core\Infrastructure\Repositories\EloquentBookingsRepository;
use App\Core\Infrastructure\Repositories\EloquentEventRepository;
use App\Core\Infrastructure\Repositories\EloquentTimeOffRepository;
use App\Core\Infrastructure\Repositories\EloquentWorkingDaysRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(IEventRepository::class, EloquentEventRepository::class);
        $this->app->bind(ITimeOffRepository::class, EloquentTimeOffRepository::class);
        $this->app->bind(IWorkingDaysRepository::class, EloquentWorkingDaysRepository::class);
        $this->app->bind(IBookingsRepository::class, EloquentBookingsRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
    }
}

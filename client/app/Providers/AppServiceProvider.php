<?php

namespace App\Providers;

use App\Services\Grpc\ConfigurableClientFactory;
use App\Services\Grpc\Contracts\ClientFactory;
use App\Services\Grpc\Contracts\ErrorHandler;
use App\Services\Grpc\LaravelErrorHandler;
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
        $this->app->bind(ClientFactory::class, ConfigurableClientFactory::class);
        $this->app->bind(ErrorHandler::class, LaravelErrorHandler::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
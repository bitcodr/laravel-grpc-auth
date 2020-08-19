<?php   namespace App\Providers;

use App\Grpc\ConfigurableClientFactory;
use App\Grpc\Interfaces\ClientFactory;
use App\Grpc\Interfaces\ErrorHandler;
use App\Grpc\LaravelErrorHandler;
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

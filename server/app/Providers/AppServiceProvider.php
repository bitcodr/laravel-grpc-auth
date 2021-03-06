<?php   namespace App\Providers;

use App\Grpc\Interfaces\Validator;
use App\Grpc\LaravelValidator;
use App\Grpc\Controllers\AuthController;
use App\Repositories\AuthRepository;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Support\ServiceProvider;
use ProtocolBuffer\Auth\AuthServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Validator::class, LaravelValidator::class);
        $this->app->bind(AuthServiceInterface::class, AuthController::class);
        $this->app->bind(AuthInterface::class, AuthRepository::class);
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

<?php

namespace App\Providers;

use App\Services\Typesense\TypesenseClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TypesenseClient::class, function () {
            return new TypesenseClient([
                'api_key' => config('services.typesense.api_key'),
                'nodes' => [
                    [
                        'host' => config('services.typesense.host'),
                        'port' => config('services.typesense.port'),
                        'protocol' => config('services.typesense.protocol'),
                    ],
                ],
                'connection_timeout_seconds' => 60 * 60,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
    }
}

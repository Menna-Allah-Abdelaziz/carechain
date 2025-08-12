<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Messaging;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return (new Factory)
                ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));
        });

        $this->app->singleton(Messaging::class, function ($app) {
            return $app->make(Factory::class)->createMessaging();
        });

        $this->app->singleton(FirebaseAuth::class, function ($app) {
            return $app->make(Factory::class)->createAuth();
        });
    }

    public function boot()
    {
        //
    }
}

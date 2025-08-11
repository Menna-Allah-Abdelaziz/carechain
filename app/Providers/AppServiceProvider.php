<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // تسجيل صلاحية المريض
        Gate::define('isPatient', function (User $user) {
            return $user->role === 'patient';
        });

        // تسجيل صلاحية المتابع (Caregiver)
        Gate::define('isCaregiver', function (User $user) {
            return $user->role === 'caregiver';
        });
    }
}

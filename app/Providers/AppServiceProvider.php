<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\MahasiswaProfile;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Explicitly bind the RoleMiddleware
        $this->app->bind('App\Http\Middleware\RoleMiddleware', function ($app) {
            return new RoleMiddleware();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Add the hasCV variable to the jobs view
        View::composer('mahasiswa.jobs', function ($view) {
            $hasCV = false;
            if (Auth::check()) {
                $user = Auth::user();
                $mahasiswa = MahasiswaProfile::where('user_id', $user->id)->first();
                $hasCV = $mahasiswa && $mahasiswa->cv && $mahasiswa->cv != '';
            }
            $view->with('hasCV', $hasCV);
        });
    }
}

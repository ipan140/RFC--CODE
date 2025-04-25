<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * 
     * After authentication, we can check the user's role and redirect them to the appropriate dashboard.
     *
     * @var string
     */
    public const HOME = '/home'; // Default if no specific redirect is provided.

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    /**
     * Override the default redirect after login based on user role.
     * 
     * This method is useful for redirecting users after authentication.
     *
     * @return string
     */
    public static function redirectToHomeBasedOnRole()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            
            switch ($role) {
                case 'admin':
                    return '/dashboardadmin'; // Admin dashboard
                case 'owner':
                    return '/dashboardowner'; // Owner dashboard
                case 'user':
                    return '/dashboarduser'; // User dashboard
                default:
                    return '/'; // Default redirect if role is unknown
            }
        }

        return self::HOME;
    }
}

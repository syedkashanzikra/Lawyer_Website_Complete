<?php

namespace Modules\LandingPage\Providers;

use Illuminate\Support\ServiceProvider;

class AddMenuProvider extends ServiceProvider
{


    public function boot()
    {

        $routes = collect(\Route::getRoutes())->map(function ($route) {
            if ($route->getName() != null) {
                return $route->getName();
            }
        });

        view()->composer($routes->toArray(), function ($view) {
            $view->getFactory()->startPush('add_menu', view('landingpage::menu.landingpage'));
        });

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}

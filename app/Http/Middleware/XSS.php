<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check()) {
            $user=\Auth::user()->lang;

            App::setLocale($user);
            if(Auth::user()->type == 'super admin')
            {
                $migrations             = $this->getMigrations();
                $dbMigrations           = $this->getExecutedMigrations();
                $Modulemigrations = glob(base_path().'/Modules/LandingPage/Database'.DIRECTORY_SEPARATOR.'Migrations'.DIRECTORY_SEPARATOR.'*.php');
                $numberOfUpdatesPending = count($migrations) - count($dbMigrations);

                $numberOfUpdatesPending = (count($migrations) + count($Modulemigrations)) - count($dbMigrations);
                if($numberOfUpdatesPending > 0)
                {
                    return redirect()->route('LaravelUpdater::welcome');
                }
            }
        }

        $input = $request->all();

        $request->merge($input);

        return $next($request);
    }
}

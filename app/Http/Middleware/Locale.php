<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ALanguages = config ('app.languages');
        //if (!$request->session()->has ('locale'))
        {
            if (!is_null(Auth::user())) {
                $locale = Auth::user()->getLocaleAttribute();
            }
            else {
                $locale = $request->getPreferredLanguage(array_keys($ALanguages));
            }
        }
        session(['locale' => $locale]);
        App::setLocale($locale);
//        setlocale (LC_TIME, app()->environment('local') ? $locale : config('app.languages')[array_keys($ALanguages)[1]]);
        return $next ($request);

    }
}

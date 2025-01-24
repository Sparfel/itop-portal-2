<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Language
     */
    public function language(String $locale)
    {
        $locale = in_array($locale, array_keys(config('app.languages'))) ? $locale : config('app.fallback_locale');
        session(['locale' => $locale]);
        App::setLocale($locale);
        //On mémorise le changement de langue
        Auth::user()->setLocaleAttribute($locale);
        return back();
    }
}

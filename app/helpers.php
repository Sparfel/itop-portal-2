<?php

use App\Models\UserPreference;
use Illuminate\Support\Facades\URL;


//Gestion des préférences
if (!function_exists('save_user_preference')) {
    function save_user_preference($userId, $key, $value)
    {
        $preference = new UserPreference();
        $preference->savePref($userId, $key, $value);
    }
}

if (!function_exists('get_user_preference')) {
    function get_user_preference($userId, $key)
    {
        $preference = new UserPreference();
        return $preference->getPref($userId, $key);
    }
}

// Permet de voir si une route es active, pour les menus ?? à vérifier
if (!function_exists('currentRouteActive')) {
    function currentRouteActive(...$routes)
    {
        foreach ($routes as $route) {
            if(Route::currentRouteNamed($route)) return 'active';
        }
    }
}

//Helper pour formater les dates
if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        return ucfirst(utf8_encode ($date->formatLocalized('%d %B %Y')));
    }
}

if (!function_exists('formatHour')) {
    function formatHour($date)
    {
        return ucfirst(utf8_encode ($date->formatLocalized('%Hh%M')));
    }
}

//Fonction pour afficher les images des posts
if (!function_exists('getImage')) {
    function getImage($post, $thumb = false)
    {
//        $url = "storage/photos/{$post->user->id}";
        $url = "storage/posts/images";
        if($thumb) $url .= '/thumbs';
        return asset("{$url}/{$post->image}");
    }
}


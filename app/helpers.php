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



// Helper pour les Status des tickets : icone et couleur
if (!function_exists('getStatusIcon')) {
    function getStatusIcon(string $status): string
    {
        $icons = [
            'new' => 'fa-solid fa-regular fa-regular fa-comment-dots',
            'escalated_tto' => 'fa fa-hourglass-start',
            'assigned' => 'fa-solid fa-user-check',
            'escalated_ttr' => 'fa fa-hourglass-end',
            'waiting_for_approval' => 'fa fa-clock',
            'reject' => 'fa fa-times-circle',
            'approved' => 'fa fa-check-circle',
            'pending' => 'fa-solid fa-hourglass-half',
            'resolved' => 'fa-regular fa-thumbs-up',
            'closed' => 'fa fa-lock',
        ];

        return $icons[strtolower($status)] ?? 'fa fa-question-circle'; // Default icon
    }
}

if (!function_exists('getStatusColor')) {
    function getStatusColor(string $status, bool $isBackground = false): string
    {
        $colors = [
            'new' => 'danger',
            'escalated_tto' => 'warning',
            'assigned' => 'info',
            'escalated_ttr' => 'danger',
            'waiting_for_approval' => 'info',
            'reject' => 'danger',
            'approved' => 'success',
            'pending' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
        ];
        $prefix = $isBackground ? 'bg-' : 'text-';

        return $prefix . ($colors[$status] ?? 'muted'); // Default color
    }
}

//Helper pour les priorités : 1 à 4
if (!function_exists('getPriorityIcon')) {
    function getPriorityIcon(int $priority): string
    {
        $icons = [
            1 => 'fa-solid fa-fire',              // Critical
            2 => 'fa-solid fa-triangle-exclamation', // High
            3 => 'fa-solid fa-circle-info',      // Medium
            4 => 'fa-solid fa-check-circle',     // Low
        ];

        return $icons[$priority] ?? 'fa fa-question-circle'; // Default icon
    }
}

if (!function_exists('getPriorityColor')) {
    function getPriorityColor(int $priority, bool $isBackground = false): string
    {
        $colors = [
            1 => 'danger',  // Critical
            2 => 'warning', // High
            3 => 'info',    // Medium
            4 => 'secondary', // Low
        ];

        $prefix = $isBackground ? 'bg-' : 'text-';

        return $prefix . ($colors[$priority] ?? 'muted'); // Default color
    }
}


//Helper pour les Types de requêtes : Incident, Service_request ou Undefined
if (!function_exists('getTypeIcon')) {
    function getTypeIcon(string $type): string
    {
        $icons = [
            'incident' => 'fa fa-life-ring',                // Incident
            'service_request' => 'fa fa-handshake',      // Service Request
            '' => 'fa-solid fa-wave-square',                  // Undefined
            'undefined' => 'fa-solid fa-wave-square',                  // Undefined
        ];

        return $icons[strtolower($type)] ?? 'fa fa-question-circle'; // Default icon
    }
}

if (!function_exists('getTypeColor')) {
    function getTypeColor(string $type, bool $isBackground = false): string
    {
        $colors = [
            'incident' => 'warning',           // Incident
            'service_request' => 'info', // Service Request
            '' => 'secondary',               // Undefined
            'undefined' => 'secondary',               // Undefined
        ];

        $prefix = $isBackground ? 'bg-' : 'text-';

        return $prefix . ($colors[strtolower($type)] ?? 'muted'); // Default color
    }
}

// Helper pour les Resolution Code des tickets : icone et couleur
if (!function_exists('getResolutioncodeIcon')) {
    function getResolutioncodeIcon(string $status): string
    {
        switch (strtolower($status)) {
            case 'assistance':
                return 'far fa-life-ring';
            case 'bug fixed':
                return 'fas fa-bug';
            case 'hardware repair':
                return 'fas fa-tools';
            case 'other':
                return 'fas fa-map-signs';
            case 'software patch':
                return 'fas fa-puzzle-piece';
            case 'system update':
                return 'fas fa-sync-alt';
            case 'training':
                return 'fas fa-graduation-cap';
            default:
                return 'fa fa-question-circle';
        }
    }
}

if (!function_exists('getResolutioncodeColor')) {
    function getResolutioncodeColor(string $status, bool $isBackground = false): string
    {
        $colors = [
            'assistance'       => 'info',
            'bug fixed'        => 'success',
            'hardware repair'  => 'warning',
            'other'            => 'secondary',
            'software patch'   => 'primary',
            'system update'    => 'dark',
            'training'         => 'info',
        ];

        $prefix = $isBackground ? 'bg-' : 'text-';
        return $prefix . ($colors[strtolower($status)] ?? 'muted');
    }
}






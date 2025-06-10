const mix = require('laravel-mix');

//Compilation du JS et du SCSS
mix.js(['resources/js/app.js'], 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

//Compilation du JS et du SCSS
mix.js('resources/vendor/admin-lte/build/js/AdminLTE.js', 'public/js/adminlte.js')
    .sass('resources/vendor/admin-lte/build/scss/adminlte.scss', 'public/css/adminlte.css');

// // Compilation des assets d'AdminLTE (JS depuis node_modules, SCSS personnalisé)
// mix.js('node_modules/admin-lte/dist/js/adminlte.js', 'public/js/adminlte.js')
//     .sass('resources/vendor/admin-lte/build/scss/adminlte.scss', 'public/css/adminlte.css');

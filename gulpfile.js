var elixir = require('laravel-elixir');

var plugins = [
    '../vendor/jquery/dist/jquery.js',
    '../vendor/bootstrap-sass/assets/javascripts/bootstrap.min.js',
    '../vendor/chosen/chosen.jquery.js',
    '../vendor/moment/moment.js',
    '../vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
];

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.browserify('app.js');
    mix.scripts(plugins, 'public/js/plugins.js');
    mix.version(['css/app.css', 'js/plugins.js', 'js/app.js']);
    mix.copy('resources/assets/vendor/bootstrap-sass/assets/fonts/bootstrap', 'public/build/fonts');
    mix.copy("resources/assets/vendor/chosen/*.png", "public/build/css/images");
    mix.browserSync({
        proxy: 'whatsloan.app'
    });
});

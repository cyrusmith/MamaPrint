/**
 * Created by Alexander Sutyagin
 * http://interosite.ru
 * info@interosite.ru
 */
require.config({
    paths: {
        '$': '../../bower_components/jquery/dist/jquery',
        'bootstrap': '../../bower_components/bootstrap/dist/js/bootstrap',
        'requireLib': '../../bower_components/requirejs/require',
        'tinymce': '../lib/tinymce/js/tinymce/tinymce.min'
    },
    urlArgs: "bust=" + (new Date()).getTime(),
    shim: {
        '$': {
            exports: 'jQuery'
        },
        bootstrap: ['$'],
        tinymce: {
            exports: 'tinymce'
        }
    },
    include: ['requireLib', 'main']
});
require(['app', 'bootstrap', 'tinymce'], function (app) {
	app.init();
});
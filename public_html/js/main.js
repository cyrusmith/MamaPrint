/**
 * Created by Alexander Sutyagin
 * http://interosite.ru
 * info@interosite.ru
 */
require.config({

    paths: {
        '$': '../bower_components/jquery/dist/jquery',
        'domReady': '../bower_components/domReady/domReady',
        'backbone': '../bower_components/backbone/backbone',
        'underscore': '../bower_components/underscore/underscore',
        'magnific': '../bower_components/magnific-popup/dist/jquery.magnific-popup',
        'twitterbootstrap': '../bower_components/bootstrap/dist/js/bootstrap',
        'headhesive': '../bower_components/headhesive.js/dist/headhesive',
        'requireLib': '../bower_components/requirejs/require',
        'promise': '../bower_components/es6-promise/promise'
    },

    urlArgs: "bust=" + Date.now(),

    shim: {
        '$': {
            exports: 'jQuery'
        },
        magnific: ['$'],
        twitterbootstrap: ['$'],
        headhesive: {
            exports: 'Headhesive'
        },
        backbone: {
            deps: ['$', 'underscore'],
            exports: 'Backbone'
        },
        underscore: {
            exports: '_'
        }
    },

    include: ['requireLib', 'main']
});

require(['bootstrap']);
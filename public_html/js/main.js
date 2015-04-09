/**
 * Created by Alexander Sutyagin
 * http://interosite.ru
 * info@interosite.ru
 */
require.config({

    paths: {
        'jquery': '../bower_components/jquery/dist/jquery',
        'domReady': '../bower_components/domReady/domReady',
        'backbone': '../bower_components/backbone/backbone',
        'underscore': '../bower_components/underscore/underscore',
        'magnific': '../bower_components/magnific-popup/dist/jquery.magnific-popup',
        'twitterbootstrap': '../bower_components/bootstrap/dist/js/bootstrap',
        'jqautocomplete': '../bower_components/jquery-ui/ui/autocomplete',
        'headhesive': '../bower_components/headhesive.js/dist/headhesive',
        'requireLib': '../bower_components/requirejs/require',
        'jqueryLazy' : '../bower_components/jquery-lazy/jquery.lazy',
        'socialLikes': '../bower_components/social-likes/social-likes.min'
    },

    urlArgs: "bust=" + Date.now(),

    shim: {
        'jquery': {
            exports: 'jQuery'
        },
        magnific: ['jquery'],
        twitterbootstrap: ['jquery'],
        jqautocomplete: ['jquery'],
        jqueryLazy: ['jquery'],
        socialLikes: ['jquery'],
        headhesive: {
            exports: 'Headhesive'
        },
        backbone: {
            deps: ['jquery', 'underscore'],
            exports: 'Backbone'
        },
        underscore: {
            exports: '_'
        }
    },

    include: ['requireLib', 'main']
});

require(['bootstrap']);
/**
 * Created by Alexander Sutyagin
 * http://interosite.ru
 * info@interosite.ru
 */
require.config({
    paths: {
        '$': '../../bower_components/jquery/dist/jquery',
        'twitterbootstrap': '../../bower_components/bootstrap/dist/js/bootstrap',
        'backbone': '../../bower_components/backbone/backbone',
        'underscore': '../../bower_components/underscore/underscore',
        'requireLib': '../../bower_components/requirejs/require',
        'tinymce': '../lib/tinymce/js/tinymce/tinymce.min'
    },
    urlArgs: "bust=" + Date.now(),
    shim: {
        '$': {
            exports: 'jQuery'
        },
        twitterbootstrap: ['$'],
        tinymce: {
            exports: 'tinymce',
			init: function () {
                this.tinyMCE.DOM.events.domLoaded = true;
                return this.tinyMCE;
            }			
        },
        backbone: {
            deps: ['$', 'underscore'],
            exports: 'Backbone'
        },
        underscore: {
            exports: '_'
        }
    },
    include: ['requireLib', 'twitterbootstrap', 'bootstrap']
});

require(['bootstrap']);
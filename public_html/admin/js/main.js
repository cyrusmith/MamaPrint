/**
 * Created by Alexander Sutyagin
 * http://interosite.ru
 * info@interosite.ru
 */
require.config({
    paths: {
        '$': '../../bower_components/jquery/dist/jquery',
        'jqueryui': '../../bower_components/jquery-ui/jquery-ui',
        'jqueryuii18n-en': '../../bower_components/jquery-ui/ui/i18n/datepicker-en-GB',
        'jqueryuii18n-ru': '../../bower_components/jquery-ui/ui/i18n/datepicker-ru',
        'twitterbootstrap': '../../bower_components/bootstrap/dist/js/bootstrap',
        'bootstrapTagsinput': '../../bower_components/bootstrap-tagsinput/src/bootstrap-tagsinput',
        'typeahead':'../../bower_components/typeahead.js/dist/typeahead.bundle',
        'backbone': '../../bower_components/backbone/backbone',
        'datetimepicker': '../../bower_components/datetimepicker/jquery.datetimepicker',
        'underscore': '../../bower_components/underscore/underscore',
        'requireLib': '../../bower_components/requirejs/require',
        'tinymce': '../lib/tinymce/js/tinymce/tinymce.min'
    },
    urlArgs: "bust=" + Date.now(),
    shim: {
        '$': {
            exports: 'jQuery'
        },
        'jqueryui': ['$'],
        'jqueryuii18n-en': ['jqueryui'],
        'jqueryuii18n-ru': ['jqueryui'],
        'datetimepicker': ['$'],
        'twitterbootstrap': ['$'],
        'bootstrapTagsinput': ['twitterbootstrap', 'typeahead'],
        'typeahead': ['$'],
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
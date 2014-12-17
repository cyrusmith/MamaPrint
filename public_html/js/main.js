/**
 * Created by Alexander Sutyagin
 * http://interosite.ru
 * info@interosite.ru
 */
require.config({

    paths: {
        '$': '../bower_components/jquery/dist/jquery',
        'magnific': '../bower_components/magnific-popup/dist/jquery.magnific-popup',
        'requireLib': '../bower_components/requirejs/require'
    },

    urlArgs: "bust=" + (new Date()).getTime(),

    shim: {
        '$': {
            exports: 'jQuery'
        },
        magnific: ['$']
    },

    include: ['requireLib', 'main']
});

require(['$', 'magnific'], function ($) {

    $(function () {
        if($('body').hasClass('page-main')) {

            $.magnificPopup.open({
                items: {
                    src: '#workbook-popup'
                },
                type: 'inline'
            }, 0);

        }


    });

});

require([
    '$',
    'cart/cart.model',
    'headhesive', 'magnific', 'twitterbootstrap'], function ($, cartModel) {

    'use strict';

    require([
        'cart/cart.link.widget',
        'catalog/catalog.item.widget'
    ], function () {
        var args = Array.prototype.slice.call(arguments, 0);

        for (var i = 0; i < args.length; i++) {
            if (typeof args[i].init === "function" && !!args[i].name) {
                var parentNode = $('[data-widget="' + args[i].name + '"]');
                if (parentNode.length > 0) {
                    parentNode.each(function () {
                        args[i].init($(this));
                    });
                }
            }
        }

    });

    $(function () {

        $(function () {
            if ($('body').hasClass('page-main')) {

                $.magnificPopup.open({
                    items: {
                        src: '#workbook-popup'
                    },
                    type: 'inline'
                }, 0);

            }
        });

        var modelsJson = $('#cart-json').text();
        var modelsData = JSON.parse(modelsJson);
        if (_.isArray(modelsData)) {
            for (var i = 0; i < modelsData.length; i++) {
                cartModel.add(modelsData[i]);
            }
        }

    });

});
require(['domReady!'], function (doc) {

    require([
        '$',
        'auth/auth.service',
        'auth/user.model',
        'cart/cart.model',
        'headhesive',
        'magnific',
        'twitterbootstrap'], function ($,
                                       authService,
                                       User,
                                       cartModel) {

        'use strict';

        require([
            'cart/cart.link.widget',
            'cart/cart.items.widget',
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

        var appConfig = JSON.parse($('#appconfig').text());
        if (appConfig.user) {
            authService.setUser(new User(appConfig.user));
        }

        var modelsJson = $('#cart-json').text();
        var modelsData = JSON.parse(modelsJson);
        if (_.isArray(modelsData)) {
            for (var i = 0; i < modelsData.length; i++) {
                cartModel.add(modelsData[i]);
            }
        }


    });

});

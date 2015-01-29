require([
    'domReady!',
    'headhesive',
    'magnific',
    'twitterbootstrap'], function (doc) {

    require([
        '$',
        'auth/auth.service',
        'auth/user.model',
        'cart/cart.model',
        'siteconfig',
        'popup'
    ], function ($,
                 authService,
                 User,
                 cartModel,
                 siteConfig,
                 popup) {

        'use strict';

        require([
            'cart/cart.link.widget',
            'cart/cart.items.widget',
            'cart/cart.itemcartbuttons.widget',
            'catalog/catalog.item.widget',
            'gallery.widget'
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

        siteConfig.init(appConfig.siteConfig);

        var modelsJson = $('#cart-json').text();
        var modelsData = JSON.parse(modelsJson);
        if (_.isArray(modelsData)) {
            for (var i = 0; i < modelsData.length; i++) {
                cartModel.add(modelsData[i]);
            }
        }

        if (!navigator.cookieEnabled) {
            popup.showCookiesWarning();
        }


    });

});

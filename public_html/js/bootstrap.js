require([
    'jquery',
    'jqueryLazy',
    'socialLikes',
    'headhesive',
    'magnific',
    'twitterbootstrap',
    'jqueryCookie',
    '../bower_components/jquery-ui/ui/autocomplete',
    'polyfills'], function ($) {

    require([
        'auth/auth.service',
        'auth/user.model',
        'auth/vklogin',
        'cart/cart.model',
        'siteconfig',
        'registerform',
        'popup',
        'search',
        'toggle',
        'registerform'
    ], function (authService,
                 User,
                 vkLogin,
                 cartModel,
                 siteConfig,
                 registerForm,
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

            $(function () {

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

        });

        $(function () {

            $('#sitepreloader').remove();

            var appConfig = JSON.parse($('#appconfig').text());
            if (appConfig.user) {
                authService.setUser(new User(appConfig.user));
                registerForm.init(!appConfig.user.guestid);
            }
            else {
                registerForm.init(false);
            }

            siteConfig.init(appConfig.siteConfig);

            vkLogin.init(appConfig.vkId, appConfig.siteBaseUrl  + '/oauth/vk');

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

            $("img.lazy").lazy({
                bind: "event"
            });

            $('.social-likes').socialLikes();

            $('[data-toggle="popover"].popoveropen').popover('show');
            $('.form-control.popoveropen').focus();

        });

    });

});

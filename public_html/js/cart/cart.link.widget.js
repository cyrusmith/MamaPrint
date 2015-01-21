define([
    '$',
    './cart.link.view',
    './cart.model'
], function ($, CartLinkView, cartModel) {

    'use strict';

    return {
        init: init,
        name: "cartlink"
    };

    function init(el) {

        var cartLinkView = new CartLinkView({
            el: el,
            model: cartModel //todo
        });

    }

});

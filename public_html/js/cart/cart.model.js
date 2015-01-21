define(['backbone', './cart.item.model'], function (Backbone, CartItem) {

    'use strict';

    var Cart = Backbone.Collection.extend({
        model: CartItem
    });

    return new Cart;

});
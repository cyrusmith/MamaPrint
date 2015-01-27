define(['backbone', './cart.item.model'], function (Backbone, CartItem) {

    'use strict';

    var Cart = Backbone.Collection.extend({

        model: CartItem,

        getTotal: function () {
            return this.reduce(function (memo, item) {
                return memo + item.get('price');
            }, 0);
        },

        isInCart: function (model) {
            return !!this.find(function (item) {
                return item.id === model.id;
            });
        }

    });

    return new Cart;

});
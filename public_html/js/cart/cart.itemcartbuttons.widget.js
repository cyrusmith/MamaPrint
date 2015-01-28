define([
    '$',
    'backbone',
    'catalog/catalog.item.model',
    'auth/auth.service',
    './cart.model',
    'popup',
    'siteconfig'
], function ($, Backbone, CatalogItemModel, authService, cartModel, popup, siteConfig) {

    'use strict';

    var ItemCartButtonsView = Backbone.View.extend({

        events: {
            'click [data-addtocart]': 'addToCart',
            'click [data-removefromcart]': 'removeFromCart'
        },

        initialize: function () {
            this.$addToCartBtn = this.$el.find('[data-addtocart]');
            this.$removeFromCartBtn = this.$el.find('[data-removefromcart]');
            this.listenTo(cartModel, "add", this.onCartModelChange);
            this.listenTo(cartModel, "remove", this.onCartModelChange);
        },

        onCartModelChange: function () {
            if (cartModel.isInCart(this.model)) {
                this.$addToCartBtn.hide();
                this.$removeFromCartBtn.css({
                    display: "inline-block"
                });
            }
            else {
                this.$addToCartBtn.show();
                this.$removeFromCartBtn.css({
                    display: "none"
                });
            }
        },

        addToCart: function (e) {
            if (!authService.getUser()) {
                popup.showLoginPrompt();
            }
            else {
                this.$addToCartBtn.removeClass('progress-hidden');
                this.$addToCartBtn.attr('disabled', 'disabled');
                Backbone.sync("create", this.model, {
                    url: "/api/v1/cart",
                    success: _.bind(function () {
                        cartModel.add(this.model);

                        if (cartModel.getTotal() >= siteConfig.getMinOrderPrice() * 100) {
                            popup.showCartPrompt()
                                .then(function () {

                                });
                        }
                    }, this),
                    complete: _.bind(function () {
                        this.$addToCartBtn.attr('disabled', null);
                        this.$addToCartBtn.addClass('progress-hidden');
                    }, this)
                });
            }
        },

        removeFromCart: function (e) {
            this.$removeFromCartBtn.removeClass('progress-hidden');
            this.$removeFromCartBtn.attr('disabled', 'disabled');
            cartModel.sync("delete", this.model, {
                url: "/api/v1/cart/" + this.model.id,
                success: _.bind(function () {
                    cartModel.remove(this.model);
                }, this),
                complete: _.bind(function () {
                    this.$removeFromCartBtn.attr('disabled', null);
                    this.$removeFromCartBtn.addClass('progress-hidden');
                }, this)
            });
        }

    });

    return {
        init: init,
        name: "itemcartbuttons"
    };

    function init(el) {
        new ItemCartButtonsView({
            el: el,
            model: new CatalogItemModel({
                id: el.attr('data-id')
            })
        });
    }

});
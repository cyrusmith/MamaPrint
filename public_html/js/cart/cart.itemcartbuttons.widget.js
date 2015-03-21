define([
    'jquery',
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
            'click [data-addtocart]': 'addToCart'
        },

        initialize: function () {
            this.$addToCartBtn = this.$el.find('[data-addtocart]');
            this.$gotoCartBtn = this.$el.find('[data-gotocart]');
            this.listenTo(cartModel, "add", this.onCartModelChange);
            this.listenTo(cartModel, "remove", this.onCartModelChange);
        },

        onCartModelChange: function () {
            if (cartModel.isInCart(this.model)) {
                this.$addToCartBtn.hide();
                this.$gotoCartBtn.css({
                    display: "inline-block"
                });
            }
            else {
                this.$addToCartBtn.show();
                this.$gotoCartBtn.css({
                    display: "none"
                });
            }
        },

        addToCart: function (e) {
            if (!authService.getUser()) {
                popup.showLoginPrompt();
            }
            else {
                this.$addToCartBtn.addClass('progress-right');
                this.$addToCartBtn.attr('disabled', 'disabled');
                Backbone.sync("create", this.model, {
                    url: "/api/v1/cart",
                    success: _.bind(function () {
                        cartModel.add(this.model);

                        if (cartModel.getTotal() >= siteConfig.getMinOrderPrice() * 100) {
                            popup.showCartPrompt();
                        }
                    }, this),
                    complete: _.bind(function () {
                        this.$addToCartBtn.attr('disabled', null);
                        this.$addToCartBtn.removeClass('progress-right');
                    }, this)
                });
            }
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
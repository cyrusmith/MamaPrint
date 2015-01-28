define([
    'backbone',
    'underscore',
    'cart/cart.model',
    'auth/auth.service',
    'popup',
    'siteconfig'
], function (Backbone, _, cartModel, authService, popup, siteConfig) {

    return Backbone.View.extend({

        events: {
            'click .catalogitem-addtocart': 'addToCart',
            'click .catalogitem-removefromcart': 'removeFromCart'
        },

        initialize: function () {
            this.listenTo(cartModel, "add", this.onCartModelChange);
            this.listenTo(cartModel, "remove", this.onCartModelChange);
            this.listenTo(this.model, "change", this.onModelChange);
            this.$addToCartBtn = this.$el.find('.catalogitem-addtocart');
            this.$removeFromCartBtn = this.$el.find('.catalogitem-removefromcart');
            this.onModelChange();
            this.onCartModelChange();
        },

        onCartModelChange: function () {

            this.model.set('inCart', !!cartModel.find(_.bind(function (item) {
                return item.id === this.model.id;
            }, this)));
        },

        onModelChange: function () {
            if (this.model.get('inCart')) {
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

});
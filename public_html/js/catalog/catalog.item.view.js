define([
    'backbone',
    'cart/cart.model',
    'auth/auth.service',
    'popup',
    'siteconfig'
], function (Backbone, cartModel, authService, popup, siteConfig) {

    return Backbone.View.extend({

        events: {
            'click .catalogitem-addtocart': 'addToCart'
        },

        initialize: function () {
            this.listenTo(cartModel, "add", this.onCartModelChange);
            this.listenTo(cartModel, "remove", this.onCartModelChange);
            this.listenTo(this.model, "change", this.onModelChange);
            this.$addToCartBtn = this.$el.find('.catalogitem-addtocart');
            this.$gotoOrderBtn = this.$el.find('.btn.goto-order');
            this.onModelChange();
            this.onCartModelChange();
        },

        onCartModelChange: function () {

            this.model.set('inCart', !!cartModel.find(function (item) {
                return item.id === this.model.id;
            }.bind(this)));
        },

        onModelChange: function () {
            if (this.model.get('inCart')) {
                this.$addToCartBtn.hide();
                this.$gotoOrderBtn.css({
                    display: "inline-block"
                });
            }
            else {
                this.$addToCartBtn.show();
                this.$gotoOrderBtn.css({
                    display: "none"
                });
            }
        },

        addToCart: function (e) {
            if (!authService.getUser()) {
                popup.showLoginPrompt();
            }
            else {
                this.$addToCartBtn.addClass('progress-left');
                this.$addToCartBtn.attr('disabled', 'disabled');
                Backbone.sync("create", this.model, {
                    url: "/api/v1/cart",
                    success: function () {
                        cartModel.add(this.model);
                        if (cartModel.getTotal() >= siteConfig.getMinOrderPrice() * 100) {
                            popup.showCartPrompt();
                        }
                    }.bind(this),
                    complete: function () {
                        this.$addToCartBtn.attr('disabled', null);
                        this.$addToCartBtn.removeClass('progress-left');
                    }.bind(this)
                });
            }
        }

    });

});
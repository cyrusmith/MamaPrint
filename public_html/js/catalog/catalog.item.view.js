define([
    'backbone',
    'underscore',
    'cart/cart.model'
], function (Backbone, _, cartModel) {

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
        },

        onCartModelChange: function () {

            this.model.set('inCart', !!cartModel.find(_.bind(function (item) {
                return item.id === this.model.id;
            }, this)));
        },

        onModelChange: function () {
            console.log("onModelChange ", this.model.get('inCart'));
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
            Backbone.sync("create", this.model, {
                url: "/api/v1/cart",
                success: _.bind(function () {
                    cartModel.add(this.model);
                }, this)
            });
        },

        removeFromCart: function (e) {
            cartModel.sync("delete", this.model, {
                url: "/api/v1/cart/" + this.model.id,
                success: _.bind(function () {
                    cartModel.remove(this.model);
                }, this)
            });
        }

    });

});
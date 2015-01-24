define([
    'backbone',
    './cart.model'
], function (Backbone, cartModel) {

    return Backbone.View.extend({

        events: {
            'click [data-cart-item-remove]': 'onRemove'
        },

        initialize: function () {
            this.$removeFromCartBtn = this.$el.find('[data-cart-item-remove]');
        },

        onRemove: function () {
            this.$removeFromCartBtn.removeClass('progress-hidden');
            this.$removeFromCartBtn.attr('disabled', 'disabled');
            cartModel.sync("delete", this.model, {
                url: "/api/v1/cart/" + this.model.id,
                success: _.bind(function () {
                    cartModel.remove(this.model);
                    this.$el.remove();
                }, this),
                complete: _.bind(function () {
                    this.$removeFromCartBtn.attr('disabled', null);
                    this.$removeFromCartBtn.addClass('progress-hidden');
                }, this)
            });

        }

    });

});
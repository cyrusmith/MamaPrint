define([
    '$',
    'backbone',
    './cart.item.view',
    './cart.item.model',
    './cart.model'
], function ($, Backbone, CartItemView, CartItem, cartModel) {

    return Backbone.View.extend({

        initialize: function () {

            this.listenTo(cartModel, "remove", this.onRemove);
            this.$total = this.$el.find('[data-cart-total]');
            this.$summary = this.$el.find('[data-summary-row]');

            this.$el.find('tr[data-cart-item]').each(function () {
                var $el = $(this);
                new CartItemView({
                    el: $el,
                    model: cartModel.get($el.attr('data-cart-item-id'))
                });
            });

            this.$total.text(cartModel.getTotal() / 100);

        },

        onRemove: function () {
            this.$total.text(cartModel.getTotal() / 100);
            if (cartModel.length === 0) {
                this.$summary.remove();
                $('.emptycart-message').show();
            }
        }

    });

});
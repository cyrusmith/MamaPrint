define([
    'backbone'
], function (Backbone) {

    return Backbone.View.extend({

        initialize: function () {
            this.listenTo(this.model, "add", this.onChange);
            this.listenTo(this.model, "remove", this.onChange);
            this.$title = this.$el.find('.btn-info .title');
        },

        onChange: function () {
            this.$title.text(this.model.length ? this.model.length + ' ед.': 'Нет товаров');
        }

    });

});
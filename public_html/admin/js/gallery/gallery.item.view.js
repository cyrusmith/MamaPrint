define([
    '$',
    'backbone',
    'underscore'], function ($,
                             Backbone,
                             _) {

    return Backbone.View.extend({

        events: {
            'click .control-delete': 'onDelete'
        },

        initialize: function () {
            this.template = _.template($('#gallery-item-tpl').html());
            this.listenTo(this.model, "destroy", this.remove);
        },

        onDelete: function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
            if (confirm('Удалить изображение?')) {
                this.model.destroy();
            }
        },

        render: function () {
            this.$el.html(this.template(_.extend({}, {id: 0}, this.model.attributes)));
            return this;
        },

        showProgress: function () {
            this.$el.find('.savecontrols').addClass('inprogress');
        },

        hideProgress: function () {
            this.$el.find('.savecontrols').removeClass('inprogress');
        }

    });

});
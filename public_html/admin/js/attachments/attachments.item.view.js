define([
    '$',
    'backbone',
    'underscore'], function ($,
                             Backbone,
                             _) {

    return Backbone.View.extend({

        events: {
            'click [data-control="removefile"]': 'onDelete'
        },

        initialize: function () {
            this.template = _.template($('#attachment-item-tpl').html());
            this.listenTo(this.model, "change", this.render);
            this.listenTo(this.model, "destroy", this.remove);
        },

        onDelete: function () {
            if (confirm('Удалить файл?')) {
                this.model.destroy();
            }
        },

        render: function () {
            console.log(this.model.attributes);
            this.$el.html(this.template(_.extend({}, {id: 0}, this.model.attributes)));
            return this;
        }

    });

});
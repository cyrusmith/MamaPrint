define([
    '$',
    'backbone',
    'underscore'], function ($,
                             Backbone,
                             _) {

    return Backbone.View.extend({

        events: {
            'click [data-control="removefile"]': 'onDelete',
            'click [data-control="savefile"]': 'onSave'
        },

        initialize: function () {

            this.template = _.template($('#attachment-item-tpl').html());
            this.listenTo(this.model, "change", this.render);
            this.listenTo(this.model, "destroy", this.remove);
            this.listenTo(this.model, "onsendcomplete", this.hideProgress);
            this.listenTo(this.model, "onbeforesend", this.showProgress);

        },

        onSave: function () {
            this.model.set({
                'title': this.$el.find('[data-field="title"]').val(),
                'description': this.$el.find('[data-field="description"]').val()
            });
            this.model.sync('update', this.model);
        },

        onDelete: function () {
            if (confirm('Удалить файл?')) {
                this.model.destroy();
            }
        }
        ,

        render: function () {
            this.$el.html(this.template(_.extend({}, {id: 0}, this.model.attributes)));
            return this;
        }
        ,

        showProgress: function () {
            this.$el.find('.savecontrols').addClass('inprogress');
        }
        ,

        hideProgress: function () {
            this.$el.find('.savecontrols').removeClass('inprogress');
        }

    })
        ;

})
;
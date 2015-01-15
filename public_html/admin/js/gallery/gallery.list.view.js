define([
    'backbone',
    './gallery.item.view',
    './gallery.image.model'
], function (Backbone, ItemView, Image) {

    return Backbone.View.extend({

        events: {
            "click [data-control='addimage']": 'addItem'
        },

        initialize: function () {
            this.listenTo(this.model, "add", this.onAdd);
            this.$list = this.$el.find('[data-container="images"]');
        },

        addItem: function () {
            this.model.add(new Image);
        },

        onAdd: function (image) {
            var item = new ItemView({
                model: image
            }).render();

            this.$list.append(item.$el);
        },

        render: function () {
        }

    });

});
define([
    'backbone',
    './attachments.item.view',
    './attachments.file.model'
], function (Backbone, ItemView, File) {

    return Backbone.View.extend({

        events: {
            "click [data-control='addfile']": 'addItem'
        },

        initialize: function () {
            this.listenTo(this.model, "add", this.onAdd);
            this.$list = this.$el.find('[data-container="attachments"]');
        },

        addItem: function () {
            this.model.add(new File);
        },

        onAdd: function (file) {
            var item = new ItemView({
                model: file
            }).render();

            this.$list.append(item.$el);
        },

        render: function () {
        }

    });

});
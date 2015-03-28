define([
    '$',
    'backbone',
    'underscore'
], function ($, Backbone, _) {

    'use strict';

    var Item = Backbone.Model.extend({
        sync: function () {
        }
    });

    var RelatedList = Backbone.Collection.extend({
        model: Item,

        sync: function () {
        }
    });

    var WidgetView = Backbone.View.extend({

        initialize: function () {
            this.list = this.$('ol');

            this.$('input[type="text"]').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "/catalog?search=" + request.term,
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });
                }.bind(this),
                minLength: 2,
                select: function (event, ui) {
                    this.model.create(ui.item);
                    return true;
                }.bind(this)
            }).autocomplete("instance")._renderItem = function (ul, item) {
                var desc = item.short_description;
                if (desc) {
                    desc = desc.split(/\s+/);
                    var ellipsis = desc.length > 0 ? " ..." : "";
                    desc = desc.slice(0, 5);
                    desc = desc.join(" ") + ellipsis;
                }
                return $("<li>")
                    .append("<a>" + item.title + "<br><small>" + desc + "</small></a>")
                    .appendTo(ul);
            };

            this.listenTo(this.model, 'add', this.addOne);
            this.listenTo(this.model, 'reset', this.addAll);
            this.listenTo(this.model, 'all', this.render);
        },

        addOne: function (item) {
            var itemView = new ItemView({model: item});
            this.list.append(itemView.render().el);
        },

        addAll: function () {
            this.model.each(this.addOne, this);
        },

        render: function () {
        }

    });

    var ItemView = Backbone.View.extend({

        tagName: 'li',

        template: _.template('<a href="/admin/catalog/edit/<%= id %>"><%= title %></a> <a class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a></li><input type="hidden" name="related[]" value="<%= id %>"/>'),

        events: {
            'click .btn-danger': "deleteItem"
        },

        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
        },

        deleteItem: function () {
            this.model.destroy();
        },

        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }

    });


    return {
        init: init,
        name: "relativesinput"
    };


    function init($el) {

        var relatedList = new RelatedList;
        relatedList.reset(JSON.parse($('#relativesinput-data').text()));
        var widget = new WidgetView({
            el: $el,
            model: relatedList
        });

        widget.addAll();
    }

});
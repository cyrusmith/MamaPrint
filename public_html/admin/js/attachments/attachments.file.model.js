define(['backbone', 'underscore'], function (Backbone, _) {
    return Backbone.Model.extend({

        rootUrl: '/attachments/{id}',

        defaults: {
            url: '/attachments',
            "title": null,
            "description": null,
            "mime": null,
            "size": 0,
            "extension": null
        },

        sync: function (method, model, options) {
            var self = this;
            options = _.extend({}, options, {
                complete: function () {
                    self.trigger("onsendcomplete");
                },
                beforeSend: function () {
                    self.trigger("onbeforesend");
                }
            })
            Backbone.Model.prototype.sync.apply(this, [method, model, options]);
        }
    });
});
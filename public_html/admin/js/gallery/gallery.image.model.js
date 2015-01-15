define(['backbone', 'underscore'], function (Backbone, _) {
    return Backbone.Model.extend({

        rootUrl: '/admin/gallery/{id}',

        defaults: {
            "title": null,
            "description": null
        }

    });
});
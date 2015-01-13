define(['backbone'], function (Backbone) {
    return Backbone.Model.extend({
        defaults: {
            "title": null,
            "description": null,
            "meta": null,
            "size": 0,
            "extension": null
        }
    });
});
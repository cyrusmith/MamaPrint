define(['backbone'], function (Backbone) {
    return Backbone.Model.extend({
        defaults: {
            "id": null,
            "name": false
        }
    });
});
define([
    '$',
    'attachments/attachments.list.view',
    'attachments/attachments.file.model'
], function ($, AttachmentsListView, File) {

    'use strict';

    return {
        init: init,
        name: "attachments"
    };

    function init(el) {

        var FilesCollection = Backbone.Collection.extend({
            url: '/admin/api/v1/attachments',
            model: File
        });

        var files = new FilesCollection;

        var attachmentsView = new AttachmentsListView({
            el: el,
            model: files
        });

        var modelsJson = $('#attachment-item-models-json').text();

        var modelsData = JSON.parse(modelsJson);

        if (_.isArray(modelsData)) {
            for (var i = 0; i < modelsData.length; i++) {
                files.add(modelsData[i]);
            }
        }

    }

});

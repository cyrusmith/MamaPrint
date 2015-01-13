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

        var files = new Backbone.Collection([], {
            model: File
        });

        var attachmentsView = new AttachmentsListView({
            el: el,
            model: files
        });

    }

});

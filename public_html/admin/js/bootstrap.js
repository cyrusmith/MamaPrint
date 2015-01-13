require([
    '$',
    'tinymce',
    'editform',
    'twitterbootstrap'], function ($, tinymce, editform) {

    'use strict';

    require([
        'attachments/attachments.widget'
    ], function () {
        var args = Array.prototype.slice.call(arguments, 0);

        for (var i = 0; i < args.length; i++) {
            if (typeof args[i].init === "function" && !!args[i].name) {
                var parentNode = $('[data-widget="' + args[i].name + '"]');
                if(parentNode.length > 0) {
                    args[i].init(parentNode);
                }
            }
        }

    });

    $(function () {

        editform.init();

        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
        });

        tinymce.init({
            selector: 'textarea.wysiwyg',
            plugins: ['advlist autolink link image lists charmap print preview',
                'responsivefilemanager'],
            external_filemanager_path: "/admin/lib/responsivefilemanager/filemanager/",
            filemanager_title: "Responsive Filemanager",
            external_plugins: {"filemanager": "/admin/lib/responsivefilemanager/filemanager/plugin.min.js"}
        });

    });

});
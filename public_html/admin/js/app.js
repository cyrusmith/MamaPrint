define(['$', 'tinymce', 'editform'], function ($, tinymce, editform) {

    'use strict';

    return new App;

    function App() {

        this.init = init;

        function init() {
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
        }

    }

});
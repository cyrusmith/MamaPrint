define(['$', 'tinymce'], function ($, tinymce) {

    'use strict';

    return new App;


    function App() {

        this.init = init;


        function init() {
            $(function () {

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
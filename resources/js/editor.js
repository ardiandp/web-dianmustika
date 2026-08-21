import tinymce from 'tinymce/tinymce';
import 'tinymce/models/dom/model';
import 'tinymce/themes/silver/theme';
import 'tinymce/icons/default/icons';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/image';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.js-tinymce')) {
        tinymce.init({
            selector: '.js-tinymce',
            height: 450,
            menubar: false,
            plugins: 'link lists image code table',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code',
            skin: false,
            content_css: false,
            content_style: 'body { font-family: "Instrument Sans", sans-serif; font-size: 16px; color: #1a1a1a; line-height: 1.6; }'
        });
    }
});

$(document).ready(function() {
    $(document).on('click', '.x-form-file-trigger', function(e) {
        e.preventDefault();

        var mediaManager = new $.MediaManagerModal({
            onSelect: function(file) {
                var $imageContainer = $('#photo').parent(),
                    $imagePreview   = $imageContainer.find('img');

                $('#photo').attr('value', file.id);
                $('#image_newimage').val(file.preview);

                if (!$imagePreview.length) {
                    $imagePreview = $('<img />').appendTo($imageContainer);
                }

                $imagePreview.css('max-width', 400).css('max-height', 300).attr('src', file.preview);
            }
        });

        mediaManager.open();
    });
});
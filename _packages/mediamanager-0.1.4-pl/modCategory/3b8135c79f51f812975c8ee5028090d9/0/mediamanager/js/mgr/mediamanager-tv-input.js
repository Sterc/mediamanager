$(document).ready(function() {

    var modalTrigger   = '.mediamanager-input',
        tvId           = null;

    $(document).on('click', modalTrigger, function(e) {
        tvId = $(this).parent().attr('data-tvid');

        var $imageContainer = $('#tv-image-preview-' + tvId),
            $imagePreview   = $imageContainer.find('img');

        e.preventDefault();

        var mediaManager = new $.MediaManagerModal({
            onSelect: function(file) {
                if (!$imagePreview.length) {
                    $imagePreview = $('<img />').appendTo($imageContainer);
                }

                if (!$imageContainer.length) {
                    // For file input types, use the raw value.
                    $('input#tv' + tvId).attr('value', file.path);
                } else {
                    $imagePreview.css('max-width', 400).css('max-height', 300).attr('src', file.preview);
                    $('input#tv' + tvId).attr('value', file.id);
                }
            }
        });

        mediaManager.open();
    });
});
$(document).ready(function() {

    var url          = '/manager/?a=home&namespace=mediamanager&tv_frame=1',
        modalWidth   = $(window).width() * 0.94,
        modalHeight  = $(window).height() * 0.94,
        modalWrapper = '#modal-wrapper',
        modalIframe  = modalWrapper + ' iframe',
        modalTrigger = '.mediamanager-input',
        useButton    = '.mediamanager-browser .view-mode-grid .file .file-options .btn-success',
        tvId         = null;

    $('body').append('<div id="modal-wrapper"><iframe class="mediamanager-iframe" id="mediamanager" src="' + url + '"></iframe></div>');

    if ($(modalWrapper).length > 0) {
        $(modalWrapper).dialog({
            title     : 'Media Manager',
            autoOpen  : false,
            width     : modalWidth,
            height    : modalHeight,
            modal     : true,
            resizable : false,
            close     : function(event, ui) {
                // Reload the iframe contents
                $(modalWrapper + ' iframe').attr('src', url);
                tvId = null;
            }
        });

        $(modalIframe).on('load', function() {
            if (tvId === null) {
                return false;
            }

            setTimeout(function() {
                selectFile($(modalIframe).contents(), tvId);
            }, 1000);
        });

        $(document).on('click', modalTrigger, function(e) {
            tvId = $(this).parent().attr('data-tvid');

            e.preventDefault();

            setTimeout(function() {
                selectFile($(modalIframe).contents(), tvId);
            }, 1000);

            $(modalWrapper).dialog('open');
        });
    }

    function selectFile($iframe, tvId) {
        $iframe.on('click', useButton, function(e) {
            var $file           = $(this).parents('.file'),
                filePreview     = $file.find('.file-preview img').data('path'),
                fileId          = $file.data('id'),
                $imageContainer = $('#tv-image-preview-' + tvId),
                $imagePreview   = $imageContainer.find('img');

            e.preventDefault();

            if (!$imagePreview.length) {
                $imagePreview = $('<img />').appendTo($imageContainer);
            }

            // If image-preview div does not exist, assume this is mm_file_input type
            if (!$imageContainer.length) {
                // for file input types, use the raw value
                $('input#tv' + tvId).attr('value', filePreview);
            } else {
                $imagePreview.css('max-width', 400).css('max-height', 300).attr('src', filePreview);
                $('input#tv' + tvId).attr('value', fileId);
            }
            $(modalWrapper).dialog('close');
        });
    }

});
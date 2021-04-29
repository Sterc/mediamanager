// Wrap your stuff in this module pattern for dependency injection
(function (jQuery, ContentBlocks) {
    // Add your custom input to the fieldTypes object as a function
    // The dom variable contains the injected field (from the template)
    // and the data variable contains field information, properties etc.
    ContentBlocks.fieldTypes.cb_mediamanager_image_input = function(dom, data) {
        var input = {
            fieldDom: dom.find('.contentblocks-field')
        };

        // Do something when the input is being loaded
        input.init = function() {
            console.log(data);
            if (data.file_id) {
                var urls = ContentBlocks.utilities.normaliseUrls(data.url);

                dom.find('.file_id').val(data.file_id);
                dom.find('.url').val(urls.cleanedSrc);
                dom.find('.contentblocks-field-image-title-input').val(data.title);
                dom.find('.contentblocks-field-image-preview img').attr('src', urls.displaySrc);
                dom.addClass('preview');
            }

            dom.find('.contentblocks-field-delete-image').on('click', function() {
                input.fieldDom.removeClass('preview');
                dom.removeClass('preview');
                dom.find('.url').val('');
                dom.find('.width').val('');
                dom.find('.height').val('');
                dom.find('.file_id').val('');
                dom.find('.contentblocks-field-image-title-input').val('');
                dom.find('.contentblocks-field-file-preview').html('');

                ContentBlocks.fixColumnHeights();
                ContentBlocks.fireChange();
            });

            dom.find('.contentblocks-field-file-choose').on('click', $.proxy(function(event) {
                event.preventDefault();
                this.chooseImage();
            }, this));
        };

        // Get the data from this input, it has to be a simple object.
        input.getData = function () {
            return {
                url         : dom.find('.url').val(),
                width       : dom.find('.width').val(),
                height      : dom.find('.height').val(),
                file_id     : dom.find('.file_id').val(),
                title       : dom.find('.contentblocks-field-image-title-input').val()
            };
        };

        input.chooseImage = function() {
            var mediaManager = new $.MediaManagerModal({
                onSelect: function(file) {
                    var url = file.preview;
                    if (url.substr(0, 4) != 'http' && url.substr(0,1) != '/' ) {
                        url = MODx.config.base_url + url;
                    }

                    dom.find('.url').val(url);
                    dom.find('.width').val('');
                    dom.find('.height').val('');
                    dom.find('.file_id').val(file.id);
                    dom.find('.contentblocks-field-image-title-input').val(file.name);
                    dom.find('.contentblocks-field-image-preview img').attr('src', file.preview);

                    input.fieldDom.addClass('preview');
                }
            });

            mediaManager.open();
        };

        // Always return the input variable.
        return input;
    }
})(jQuery, ContentBlocks);
// Wrap your stuff in this module pattern for dependency injection
(function (jQuery, ContentBlocks) {
    // Add your custom input to the fieldTypes object as a function
    // The dom variable contains the injected field (from the template)
    // and the data variable contains field information, properties etc.
    ContentBlocks.fieldTypes.cb_mediamanager_image_input = function(dom, data) {
        var input = {
            fieldDom: dom.find('.contentblocks-field'),
            cropData: data.crops || {},
            cropPreviews: dom.find('.contentblocks-field-image-crop-previews'),
            openCropperAutomatically: ContentBlocks.toBoolean(data.properties.open_crops_automatically)
        };

        // Do something when the input is being loaded
        input.init = function() {
            if (data.file_id) {
                var urls = ContentBlocks.utilities.normaliseUrls(data.url);

                dom.find('.file_id').val(data.file_id);
                dom.find('.url').val(urls.cleanedSrc);
                dom.find('.width').val(data.width);
                dom.find('.height').val(data.height);
                dom.find('.contentblocks-field-image-title-input').val(data.title);
                dom.find('.contentblocks-field-image-preview img').attr('src', urls.displaySrc);
                dom.addClass('preview');
                input.initCropPreviews();
            }

            if (!data.properties.crops || data.properties.crops.length === 0) {
                input.fieldDom.find('.contentblocks-field-crop-image').hide();
            }
            else {
                input.fieldDom.find('.contentblocks-field-crop-image').on('click', input.openCropper);
                input.fieldDom.on('click', '.contentblocks-field-image-crop-preview', function(e) {
                    var crop = $(this),
                        cropKey = crop.data('key');
                    input.openCropper(e, cropKey);
                });
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
                input.cropData = data.crops = {};
                input.cropPreviews.empty();

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
                title       : dom.find('.contentblocks-field-image-title-input').val(),
                crops       : input.cropData || {}
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

                    if (input.openCropperAutomatically) {
                        input.openCropper();
                    }
                }
            });

            mediaManager.open();
        };

        input.initCropPreviews = function() {
            $.each(input.cropData, function(key, cropData) {
                if (cropData.url && data.properties.crops && data.properties.crops.indexOf(key) !== -1) {
                    var cd = $.extend({cropKey: key}, cropData);
                    input.cropPreviews.append(tmpl('contentblocks-field-image-crop', cd));
                }
            });
        };

        input.openCropper = function(e, crop) {
            var imgData = $.extend(true, {}, data, input.getData());
            crop = crop || false;
            var cropper = ContentBlocks.Cropper(imgData, {
                configurations: data.properties.crops || '',
                initialCrop: crop
            });
            cropper.onChange(function(cropperData) {
                input.cropData = $.extend(true, {}, cropperData, true);
                $.each(cropperData, function(cropKey, cropData) {
                    if (!cropData.url) {
                        return;
                    }
                    var preview = input.cropPreviews.find('.image-crop-' + cropKey + ' img');
                    if (preview && preview.length) {
                        preview.attr('src', cropData.url)
                    }
                    else {
                        var cd = $.extend({cropKey: cropKey}, cropData);
                        input.cropPreviews.append(tmpl('contentblocks-field-image-crop', cd));
                    }
                });
                if (data.properties.thumbnail_crop
                    && input.cropData[data.properties.thumbnail_crop]
                    && input.cropData[data.properties.thumbnail_crop].url) {
                    input.fieldDom.find('img.contentblocks-field-image-preview-img').attr('src', input.cropData[data.properties.thumbnail_crop].url);
                }
            });
        };

        // Always return the input variable.
        return input;
    }
})(jQuery, ContentBlocks);
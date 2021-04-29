(function (jQuery, ContentBlocks) {
    ContentBlocks.fieldTypes.cb_mediamanager_gallery_input = function(dom, data) {
        var input = {
            imageCount: 0,
            fileBrowser: false,
            source: data.properties.source > 0 ? data.properties.source : ContentBlocksConfig['image.source'],
            directory: data.properties.directory
        };

        input.init = function () {
            this.initUpload();

            // override the thumbnail size classname if we're specifying thumbnail dimensions
            if (data.properties.thumbnail_size > 0 || (data.properties.thumbnail_size && data.properties.thumbnail_size.length > 1)) {
                data.properties.thumb_size = 'specified';
            }

            dom.find('.contentblocks-field-gallery-list').addClass('gallery-size-' + data.properties.thumb_size);

            dom.find('.contentblocks-field-upload').on('click', function() {
                dom.find('.contentblocks-field-upload-field').click();
            });
            dom.find('.contentblocks-field-gallery-choose').on('click', $.proxy(function(event) {
                event.preventDefault();
                this.chooseImage();
            }, this));

            if ($.isArray(data.images)) {
                $.each(data.images, function(idx, img) {
                    input.imageCount++;
                    img.id = data.generated_id + '-image' + input.imageCount;
                    img.width = img.width || 0;
                    img.height = img.height || 0;
                    input.addImage(img, 'init');
                });
            }
            dom.find('.gallery-image-holder').sortable({
                connectWith: '.gallery-image-holder',
                forceHelperSize: true,
                forcePlaceholderSize: true,
                placeholder: 'contentblocks-gallery-placeholder',
                tolerance: 'pointer',
                cursor: 'move',

                update: function() {
                    ContentBlocks.fixColumnHeights();
                    ContentBlocks.fireChange();
                },

                start: function(event, ui) {
                    ui.placeholder.height(ui.item.height());
                }
            });

            if (!ContentBlocks.toBoolean(data.properties.show_description)) {
                dom.addClass('no-description');
            }

            if (!ContentBlocks.toBoolean(data.properties.show_link_field)) {
                dom.addClass('no-link-field');
            }
        };

        input.chooseImage = function() {
            var maxImages = data.properties.max_images,
                numImages = dom.find('.gallery-image-holder').find('li').length;

            if (maxImages > 0 && numImages >= maxImages) {
                ContentBlocks.alert(_('contentblocks.max_images_reached', {max: maxImages}));
                return false;
            }

            /* Show Media Manager */
            var mediaManager = new $.MediaManagerModal({
                onSelect: function(file) {
                    input.chooseImageCallback(file);
                }
            });

            mediaManager.open();
        };

        input.chooseImageCallback = function(imageData) {
            var url = imageData.preview;
            if (url.substr(0, 4) != 'http' && url.substr(0,1) != '/' ) {
                url = MODx.config.base_url + url;
            }

            input.imageCount++;
            //var imageId = dom.attr('id') + '-image' + input.imageCount;
            //var size = imageData.size;
            //var extension = imageData.ext;

            this.addImage({
                url         : url,
                title       : imageData.name,
                description : '',
                link        : '',
                id          : imageData.id,
                size        : '',
                width       : '',
                height      : '',
                extension   : '',
                file_id     : imageData.id
            }, 'choose');
        };

        input.addImage = function(values, source) {
            var holder = dom.find('.gallery-image-holder'),
                urls = ContentBlocks.utilities.normaliseUrls(values.url);

            values.url = urls.cleanedSrc;
            values.description = values.description || '';
            values.link = values.link || '';
            values.thumbDisplay = (data.properties.thumbnail_size)
                ? ContentBlocks.utilities.getThumbnailUrl(values.url, data.properties.thumbnail_size)
                : urls.displaySrc;

            holder.append(tmpl('contentblocks-field-gallery-item', values));
            var inserted = $('#' + values.id);
            inserted.find('.contentblocks-gallery-image-delete').on('click', function() {
                inserted.fadeOut(function() {
                    inserted.remove();
                    ContentBlocks.fixColumnHeights();
                    ContentBlocks.fireChange();
                });
            });

            if (ContentBlocks.toBoolean(data.properties.show_link_field)) {
                ContentBlocks.initializeLinkField(inserted.find('.linkfield'), data);
            }
        };

        input.initUpload = function() {
            var id = dom.attr('id'),
                maxImages = data.properties.max_images;

            dom.find('#' + id + '-upload').fileupload({
                url: ContentBlocksConfig.connectorUrl + '?action=content/image/upload',
                dataType: 'json',
                dropZone: $('#' + id),
                progressInterval: 250,
                paramName: 'file',
                multiple: true,
                pasteZone: null,

                /**
                 * Add an item to the upload queue.
                 *
                 * The item gets an image preview using the FileReader APIs if available
                 *
                 * @param e
                 * @param data
                 */
                add: function(e, data) {
                    // Check if we're not at the limit already
                    var numImages = dom.find('.gallery-image-holder').find('li').length;
                    if (maxImages > 0 && numImages >= maxImages) {
                        ContentBlocks.alert(_('contentblocks.max_images_reached', {max: maxImages}));
                        return false;
                    }

                    input.imageCount++;
                    var imageId = id + '-image' + input.imageCount;
                    data.files[0].ext = data.files[0].name.split('.').pop();

                    // Add image to the page
                    input.addImage({
                        title: data.files[0].name,
                        url: '',
                        id: imageId,
                        size: data.files[0].size,
                        extension: data.files[0].ext,
                        file_id: data.files[0].file_id
                    }, 'upload');
                    data.domId = '#' + imageId;

                    var img = $(data.domId);
                    img.addClass('uploading');
                    if (data.files[0].size < 700000 && window.FileReader) {
                        // Only if the image is smaller than ~ 700kb we want to show a preview.
                        // This is to prevent filling up the users' RAM, while providing the user
                        // with a fancy preview of what they're uploading.
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            img.find('img').attr('src', e.target.result);
                            ContentBlocks.fixColumnHeights();
                        };
                        reader.readAsDataURL(data.files[0]);
                    }

                    setTimeout(function() {
                        data.submit();
                    }, 1000);

                    ContentBlocks.fireChange();
                },

                /**
                 * When the image has been uploaded add it to the collection.
                 *
                 */
                done: function(e, responseData) {
                    var dom = $(responseData.domId);
                    if (responseData.result.success) {
                        var record = responseData.result.object,
                            urls = ContentBlocks.utilities.normaliseUrls(record.url);
                        dom.find('.url').val(urls.cleanedSrc);
                        dom.find('.size').val(record.size);
                        dom.find('.width').val(record.width);
                        dom.find('.height').val(record.height);
                        dom.find('.extension').val(record.extension);
                        dom.find('.file_id').val(record.file_id);

                        var url = (data.properties.thumbnail_size)
                            ? ContentBlocks.utilities.getThumbnailUrl(record.url, data.properties.thumbnail_size)
                            : urls.displaySrc;
                        dom.find('img').attr('src', url);

                        dom.removeClass('uploading');
                    }
                    else {
                        var message = _('contentblocks.upload_error', {file: responseData.files[0].filename, message: responseData.result.message});
                        if (responseData.files[0].size > 1048576*1.5) {
                            message += _('contentblocks.upload_error.file_too_big');
                        }
                        ContentBlocks.alert(message);
                        dom.remove();
                    }

                    setTimeout(function() {
                        ContentBlocks.fixColumnHeights();
                    }, 150);
                },

                fail: function(e, data) {
                    var message = _('contentblocks.upload_error', {file: data.files[0].filename, message:  data.result.message});
                    if (data.files[0].size > 1048576*1.5) {
                        message += _('contentblocks.upload_error.file_too_big');
                    }
                    ContentBlocks.alert(message);

                    $(data.domId).remove();
                    ContentBlocks.fixColumnHeights();
                },

                /**
                 * Fetch the items we want to send along in the POST. In this case,
                 * this is overridden because normally it sends the entire form = the resource.
                 * All we really want is the resource ID, which we fetch from the URL.
                 * @returns {Array}
                 */
                formData: function() {
                    return [{
                        name: 'HTTP_MODAUTH',
                        value: MODx.siteId
                    },{
                        name: 'resource',
                        value: MODx.request.id || 0
                    },{
                        name: 'field',
                        value: data.id
                    }];
                },

                /**
                 * Update progress for queue items
                 */
                progress: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10) + '%';
                    $(data.domId).find('.upload-progress .bar').width(progress);
                }
            }).on('fileuploaddragover', function() {
                $(this).css('background', 'red');
            });
        };

        input.getData = function () {
            var images = [];
            dom.find('.gallery-image-holder li').each(function(idx, img) {
                var $img = $(img),
                    $link = $img.find('input[id].linkfield'),
                    data = {
                        url: $img.find('.url').val(),
                        title: $img.find('.title').val(),
                        description: $img.find('.description').val(),
                        link: $link.val(),
                        linkType: ContentBlocks.getLinkFieldDataType($link.val()),
                        size: $img.find('.size').val(),
                        width: $img.find('.width').val(),
                        height: $img.find('.height').val(),
                        extension: $img.find('.extension').val(),
                        file_id: $img.find('.file_id').val()
                    };
                images.push(data);
            });

            return {
                images: images
            };
        };

        return input;
    };
})(jQuery, ContentBlocks);

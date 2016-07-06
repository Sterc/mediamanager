// Wrap your stuff in this module pattern for dependency injection
(function (jQuery, ContentBlocks) {
    // Add your custom input to the fieldTypes object as a function
    // The dom variable contains the injected field (from the template)
    // and the data variable contains field information, properties etc.
    ContentBlocks.fieldTypes.cb_mediamanager_input = function(dom, data) {
        var input = {

        };

        // Do something when the input is being loaded
        input.init = function() {
            if (data.file_id && data.file_id.length) {
                dom.find('.file_id').val(data.file_id);
                dom.find('.file_name').val(data.file_name);
                dom.find('.contentblocks-field-file-preview').html(data.file_name);
                dom.addClass('preview');
            }

            dom.find('.contentblocks-field-delete-file').on('click', function() {
                dom.removeClass('preview');
                dom.find('.file_id').val('');
                dom.find('.file_name').val('');
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
                file_id: dom.find('.file_id').val(),
                file_name: dom.find('.file_name').val()
            };
        };

        input.chooseImage = function() {
            var mediaManager = new $.MediaManagerModal({
                onSelect: function(file) {
                    dom.find('.file_id').val(file.id);
                    dom.find('.file_name').val(file.name);
                    dom.find('.contentblocks-field-file-preview').html(file.name);
                }
            });

            mediaManager.open();
        };

        // Always return the input variable.
        return input;
    }
})(jQuery, ContentBlocks);
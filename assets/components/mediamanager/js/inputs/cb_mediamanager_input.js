// Wrap your stuff in this module pattern for dependency injection
(function (jQuery, ContentBlocks) {
    // Add your custom input to the fieldTypes object as a function
    // The dom variable contains the injected field (from the template)
    // and the data variable contains field information, properties etc.
    ContentBlocks.fieldTypes.cb_mediamanager_input = function(dom, data) {
        var input = {
            // Some optional variables can be defined here
        };

        // Do something when the input is being loaded
        input.init = function() {
            if(!$('#cb-modal-wrapper').length) {
                var modal = '<div id="cb-modal-wrapper"><iframe class="mediamanager-iframe" id="cb_mediamanager" src="/manager/?a=home&namespace=mediamanager&tv_frame=1"></iframe></div>';
                $('body').append(modal);

                var modalWidth = $(window).width() * 0.94;
                var modalHeight = $(window).height() * 0.94;
                var modalWrapper = '#cb-modal-wrapper';

                $(modalWrapper).dialog({
                    title: 'Media Manager',
                    autoOpen: false,
                    width: modalWidth,
                    height: modalHeight,
                    modal: true,
                    resizable: false,
                    close: function(event,ui) {
                        // reload the iframe contents
                        $(modalWrapper+' > iframe').attr('src',$(modalWrapper+' > iframe').attr('src'));
                    }
                });
            }

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

            dom.find('.contentblocks-field-file-choose').on('click', $.proxy(function() {
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
            var modalWrapper = '#cb-modal-wrapper';

            $(modalWrapper).dialog('open');

            setTimeout(function(){
                $(modalWrapper+' iframe').contents().find('.tv-tiny-use').on('click', function(event) {
                    dom.find('.file_id').val($(this).parents('[data-id]').attr('data-id'));
                    dom.find('.file_name').val($(this).parents('[data-id]').find('.file-name').text());
                    dom.find('.contentblocks-field-file-preview').html($.trim($(this).parents('[data-id]').find('.file-name').text()));

                    $(modalWrapper).dialog('close');
                });
            }, 1000);
        };

        // Always return the input variable.
        return input;
    }
})(jQuery, ContentBlocks);

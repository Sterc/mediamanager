(function($) {
    var manager_url = '/manager/';

    if (MODx.config) {
        manager_url = MODx.config.manager_url;
    }

    var defaults = {
        url           : manager_url + '?a=home&namespace=mediamanager&tv_frame=1',
        width         : $(window).width() * 0.94,
        height        : $(window).height() * 0.94,
        wrapperId     : 'modal-wrapper',
        selectElement : '.mediamanager-browser .file .file-options .btn-success',
        onSelect      : function() {}
    };

    $.MediaManagerModal = function(options) {
        var settings = $.extend({}, defaults, options);

        this.open = function() {
            $('body').append('<div id="' + settings.wrapperId + '"><iframe class="mediamanager-iframe" id="mediamanager" src=""></iframe></div>');

            var $dialogElement = $('#' + settings.wrapperId),
                $iFrameElement = $('#' + settings.wrapperId + ' > iframe');

            $iFrameElement.unbind('load');

            $dialogElement.dialog({
                title     : 'Media Manager',
                autoOpen  : false,
                width     : settings.width,
                height    : settings.height,
                modal     : true,
                resizable : false,
                close : function(event, ui) {
                    $dialogElement.remove();
                },
                open : function(event, ui) {
                    $iFrameElement.attr('src', settings.url);
                }
            });

            $iFrameElement.on('load', function() {
                var $iFrame = $(this).contents();

                $iFrame.unbind('click');
                $iFrame.on('click', settings.selectElement, function(e) {
                    e.preventDefault();

                    var $file       = $(this).parents('.file'),
                        fileId      = $file.data('id'),
                        fileName    = $file.data('name'),
                        filePreview = $file.data('preview');

                    var object = {
                        'id'      : fileId,
                        'name'    : fileName,
                        'preview' : filePreview
                    };

                    settings.onSelect(object);

                    $dialogElement.dialog('close');
                });
            });

            $dialogElement.dialog('open');
        };
    };
}(jQuery));

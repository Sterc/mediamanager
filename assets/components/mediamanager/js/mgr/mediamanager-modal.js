(function ($) {
    var manager_url = '/manager/';
    if (MODx.config) {
        manager_url = MODx.config.manager_url;
    }

    var defaults = {
        url           : manager_url + '?a=home&namespace=mediamanager&tv_frame=1',
        width         : $(window).width() * 0.94,
        height        : $(window).height() * 0.94,
        wrapperId     : 'modal-wrapper',
        selectElement : '.mediamanager-browser .view-mode-grid .file .file-options .btn-success',
        selectMultiElement : '.use-multi',
        onSelect      : function () {},
        onSelectMulti : function() {}
    }

    $.MediaManagerModal = function (options) {
        var settings = $.extend({}, defaults, options);

        this.open = function () {
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
                close : function (event, ui) {
                    $dialogElement.remove();
                },
                open : function (event, ui) {
                    $iFrameElement.attr('src', settings.url);
                }
            });

            function buildObject($file) {
                return {
                    preview: $file.find('.file-preview img, .file-preview svg').data('path'),
                    path: $file.find('.file-preview img, .file-preview svg').data('path'),
                    id: $file.data('id'),
                    name: $.trim($file.find('.file-name').text())
                };
            }

            $iFrameElement.on('load', function() {
                var $iFrame = $(this).contents();

                $iFrame.unbind('click');
                $iFrame.on('click', settings.selectElement, function(e) {
                    e.preventDefault();

                    let $file = $(this).parents('.file');
                    let object = buildObject($file)

                    settings.onSelect(object);

                    $dialogElement.dialog('close');
                });

                if (settings.onSelectMulti !== function() {}) {
                    const batchUseButtons = $iFrame.find(settings.selectMultiElement);
                    if (batchUseButtons.length !== 1) {
                        return
                    }
                    batchUseButtons[0].classList.remove('hidden')
                    batchUseButtons[0].addEventListener(
                        'files_selected',
                        function (event) {
                            let objects = []
                            event.detail.files.forEach(function (file) {
                                let $file = $iFrame.find("[data-id='"+ file.id + "']")
                                if ($file.length === 0) {
                                    return
                                }
                                objects.push(buildObject($file))
                            })

                            if (objects.length === 0) {
                                return
                            }
                            settings.onSelectMulti(objects)
                            $dialogElement.dialog('close');
                        }
                    )
                }
            });

            $dialogElement.dialog('open');
        }
    }
}(jQuery));

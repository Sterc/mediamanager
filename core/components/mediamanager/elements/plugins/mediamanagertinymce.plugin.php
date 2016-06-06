<?php
$modx->regClientStartupHTMLBlock("
    <script>
        Ext.override(TinyMCERTE.Tiny, {
            loadBrowser : function(){
                mmLoadBrowser();
                return false;
            }
        });

        function mmLoadBrowser() {
            var modalWrapper = '#modal-wrapper';

            $('#mce-modal-block').hide();
            $('.mce-window').css('z-index', '1');

            $(modalWrapper).dialog('open');

            setTimeout(function() {
                $(modalWrapper + ' iframe').contents().find('.mediamanager-browser .view-mode-grid .file .file-options .btn-success').on('click', function(event) {
                    var filePath = $(this).parents('.file').find('.file-preview img').data('path');

                    $('.mce-window').find('.mce-textbox.mce-placeholder').attr('value', filePath);

                    $(modalWrapper).dialog('close');
                });
            }, 1000);
        }
    </script>
");
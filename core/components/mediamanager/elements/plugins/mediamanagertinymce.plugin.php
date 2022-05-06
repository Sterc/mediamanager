<?php
switch ($modx->event->name) {
    case 'OnDocFormPrerender':
        $modx->regClientStartupHTMLBlock("
            <script>
                if (typeof TinyMCERTE !== 'undefined') {
                    Ext.override(TinyMCERTE.Tiny, {
                        loadBrowser : mmLoadBrowser,
                    });
                }

                function mmLoadBrowser (callback, url, meta) {
                    $('.mce-window').css('z-index', '1');
                    $('#mce-modal-block').hide();

                    var mediaManager = new $.MediaManagerModal({
                        onSelect: function (file) {
                            callback(file.path, {});
                        }
                    });

                    mediaManager.open();
                }
            </script>
        ");

        break;

}
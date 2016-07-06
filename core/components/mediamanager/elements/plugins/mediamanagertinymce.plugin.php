<?php
switch ($modx->event->name) {

    case 'OnDocFormPrerender':

        $modx->regClientStartupHTMLBlock("
            <script>
                if(typeof TinyMCERTE !== 'undefined'){
                    Ext.override(TinyMCERTE.Tiny, {
                        loadBrowser : function(field_name, url, type, win){
                            mmLoadBrowser(field_name, url, type, win);
                            return false;
                        }
                    });
                }
                
                function mmLoadBrowser(field_name, url, type, win) {
                    $('.mce-window').css('z-index', '1');
                    $('#mce-modal-block').hide();
                    
                    var mediaManager = new $.MediaManagerModal({
                        onSelect: function(file) {
                            win.document.getElementById(field_name).value = file.path;
                        }
                    });
                    
                    mediaManager.open();
                }
            </script>
        ");

        break;

}
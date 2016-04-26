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
            
            $('#mce-modal-block').hide();
            $('.mce-window').css('z-index','1');
            
            var modalWrapper = '#modal-wrapper';
            $(modalWrapper).dialog('open');
            setTimeout(function(){
                $(modalWrapper+' iframe').contents().find('.tv-tiny-use').on('click', function(event) {
                    var selectedValue = $(this).parent('.file-preview').find('img').attr('data-path');
                    $('.mce-window').find('.mce-textbox.mce-placeholder').attr('value',selectedValue);
                    $(modalWrapper).dialog('close');
                });
            }, 200);
        }
    </script>
");
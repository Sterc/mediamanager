$(document).ready(function() {

    var selectedValue = '';
    var modal = '<div id="modal-wrapper"><iframe class="mediamanager-iframe" id="mediamanager" src="/manager/?a=home&namespace=mediamanager&tv_frame=1"></iframe></div>';
    $('body').append(modal);

    var modalWidth = $(window).width() * 0.94;
    var modalHeight = $(window).height() * 0.94;
    var modalWrapper = '#modal-wrapper';
    var modalTrigger = '.mediamanager-input';

    if(modalWrapper && $(modalWrapper).length > 0) {

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

        $(modalTrigger).on('click',function(e){
            e.preventDefault();
            var tvId = $(this).parent().attr('data-tvid');
            $(modalWrapper).dialog('open');

            // using settimeout for correctly setting selected value when first initializing modal :S
            setTimeout(function(){
                $(modalWrapper+' iframe').contents().find('.tv-tiny-use').on('click', function(event) {
                    var selectedValue = $(this).parent('.file-preview').find('img').attr('data-path');
                    $('#tv-image-preview-'+tvId).find('img').attr('src',selectedValue);
                    $('input#tv'+tvId).attr('value',selectedValue);
                    $(modalWrapper).dialog('close');
                });
            }, 200);

        });

        // $('.mce-combobox button').on('click',function(){
        //     console.log('osidf');
        //     e.preventDefault();
        //     return false;
        // });

    }

});
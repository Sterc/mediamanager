$(document).ready(function() {

    $('#dialogWrapper').dialog({
        autoOpen: false,
        width: 1000, 
        height:1000,
        modal: true
    });

    $('#selectFile').on('click',function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        // $('#dialog').load(link,function(){
        //     $('#dialogWrapper' ).dialog( 'open' );   
        // }); 
        $('iframe#mediamanager').attr('src', link);
        $('#dialogWrapper' ).dialog('open');   
    });

});

Ext.onReady(function() {

    var helpWindow = new Ext.Window({
        title: 'Preview',
        modal: true,
        // width: 850,
        // height: 500,
        resizable: true,
        maximizable: true,
        layout: 'fit',
        autoWidth: true,
        autoHeight: true,
        autoScroll: false,
        html: '<iframe id="mediamanager" src="http://mediamanager.nl.joeke/manager/?a=home&namespace=mediamanager"></iframe>'
        // ,items : [{
        //     xtype : "component"
        //     ,autoEl : {
        //         tag : "iframe",
        //         src : "http://mediamanager.nl.joeke/manager/?a=home&namespace=mediamanager"
        //     }
        // }]
    });
    helpWindow.show();

});
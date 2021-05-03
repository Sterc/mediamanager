var MediaManager = function (e) {
    e = e || {},
    MediaManager.superclass.constructor.call(this, e);
};

Ext.extend(MediaManager, Ext.Component, {
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {},
    jquery  : {},
    form    : {},
    generateThumbUrl: function (id) {
        if (typeof Ext.Ajax.extraParams !== 'undefined') {
            return MediaManager.config.assets_url + 'connector.php?action=mgr/migx/getfile&src=' + id + '&HTTP_MODAUTH=' + Ext.Ajax.extraParams.HTTP_MODAUTH + '&wctx=mgr';
        }

        return '';
    }
});
Ext.reg('MediaManager', MediaManager);

MediaManager = new MediaManager();
MediaManager.MIGX_Image_Renderer = function (id) {
    if (id === null || parseInt(id) < 1) {
        return '';
    }

    return '<img src="' + MediaManager.generateThumbUrl(id) + '" style="width: auto; height: 60px;" />';
};

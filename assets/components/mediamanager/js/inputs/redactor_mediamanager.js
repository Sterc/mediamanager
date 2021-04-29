(function($R)
{
    $R.add('plugin', 'mediamanager', {
        init: function(app)
        {
            this.app        = app;
            this.toolbar    = app.toolbar;
        },

        start: function() {
            var pos = this.toolbar.buttons.indexOf('mediamanager'),
                prev = null;

            if (pos >= 1) {
                prev = this.toolbar.buttons[pos - 1];
            }

            var $button = this.toolbar.addButtonAfter(prev, 'mediamanager', {
                title   : 'Media Manager',
                api     : 'plugin.mediamanager.openMediaMananger'
            });

            $button.setIcon('<i class="re-icon re-icon-image"></i>');
        },

        openMediaMananger: function() {
            var modal = new $.MediaManagerModal({
                onSelect : (function(file) {
                    this.app.api('module.image.insert', {
                        image   : {
                            id      : file.preview,
                            url     : file.preview
                        }
                    });
                }).bind(this)
            });

            modal.open();
        },
    });
})(Redactor);
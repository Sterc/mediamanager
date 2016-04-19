var MediaManagerFilesCropper = {

    $image             : null,

    $mediaManagerFiles : null,

    $dataX             : 'input[data-crop-x]',
    $dataY             : 'input[data-crop-y]',
    $dataWidth         : 'input[data-crop-width]',
    $dataHeight        : 'input[data-crop-height]',
    $dataRotate        : 'input[data-crop-rotate]',
    $dataScaleX        : 'input[data-crop-scale-x]',
    $dataScaleY        : 'input[data-crop-scale-y]',

    $buttons           : '.crop-buttons',
    $tooltip           : '[data-toggle="tooltip"]',

    $options           : {
        //aspectRatio: 16 / 9,
        viewMode : 1,
        dragMode : 'move',
        preview  : '.img-preview'
    },

    init: function(element, MediaManagerFiles) {
        var self = this;

        self.$image             = element;
        self.$mediaManagerFiles = MediaManagerFiles;
        self.$dataX             = $(self.$dataX);
        self.$dataY             = $(self.$dataY);
        self.$dataWidth         = $(self.$dataWidth);
        self.$dataHeight        = $(self.$dataHeight);
        self.$dataRotate        = $(self.$dataRotate);
        self.$dataScaleX        = $(self.$dataScaleX);
        self.$dataScaleY        = $(self.$dataScaleY);

        self.$options.crop = function(e) {
            self.$dataX.val(Math.round(e.x));
            self.$dataY.val(Math.round(e.y));
            self.$dataHeight.val(Math.round(e.height));
            self.$dataWidth.val(Math.round(e.width));
            self.$dataRotate.val(e.rotate);
            self.$dataScaleX.val(e.scaleX);
            self.$dataScaleY.val(e.scaleY);
        };

        $(self.$tooltip).tooltip();

        self.cropper();
        self.events();
    },

    cropper: function() {
        var self = this;

        self.$image.cropper(self.$options);
    },

    buttons: function(e) {
        var self    = this,
            $button = $(e.currentTarget),
            data    = $button.data(),
            $target = null,
            result  = null;

        if ($button.prop('disabled') || $button.hasClass('disabled')) {
            return;
        }

        if (self.$image.data('cropper') && data.method) {
            data = $.extend({}, data); // Clone a new one

            if (typeof data.target !== 'undefined') {
                $target = $(data.target);

                if (typeof data.option === 'undefined') {
                    try {
                        data.option = JSON.parse($target.val());
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }

            result = self.$image.cropper(data.method, data.option, data.secondOption);

            switch (data.method) {
                case 'scaleX':
                case 'scaleY':
                    $(this).data('option', -data.option);
                    break;

                case 'getCroppedCanvas':
                    if (result) {

                        $.ajax({
                            url: self.$mediaManagerFiles.$connectorUrl,
                            method: 'post',
                            data: {
                                action       : 'mgr/files',
                                method       : 'crop',
                                HTTP_MODAUTH : self.$mediaManagerFiles.$httpModAuth,
                                fileId       : self.$mediaManagerFiles.$currentFile,
                                cropData     : result.toDataURL()
                            }
                        }).success(function(data) {
                            console.log(data);
                        });

                    }

                    break;
            }

            if ($.isPlainObject(result) && $target) {
                try {
                    $target.val(JSON.stringify(result));
                } catch (e) {
                    console.log(e.message);
                }
            }

        }
    },

    events: function() {
        var self = this;

        $(self.$buttons).on('click', '[data-method]', function(event) {
            self.buttons(event);
        });
    }

}

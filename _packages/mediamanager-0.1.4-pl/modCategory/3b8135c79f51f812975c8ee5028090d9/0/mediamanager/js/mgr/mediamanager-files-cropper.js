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
    $tooltip           : 'span[data-toggle="tooltip"]',
    $messageContainer  : 'div[data-crop-message]',

    $options           : {
        viewMode : 1,
        dragMode : 'move',
        preview  : '.img-preview'
    },

    init: function(element, MediaManagerFiles) {
        var self = this;

        self.$image             = element;
        self.$mediaManagerFiles = MediaManagerFiles;

        self.$options.crop = function(e) {
            $(self.$dataX).val(Math.round(e.x));
            $(self.$dataY).val(Math.round(e.y));
            $(self.$dataHeight).val(Math.round(e.height));
            $(self.$dataWidth).val(Math.round(e.width));
            $(self.$dataRotate).val(e.rotate);
            $(self.$dataScaleX).val(e.scaleX);
            $(self.$dataScaleY).val(e.scaleY);
        };

        $(self.$tooltip).tooltip();

        self.cropper();
        self.setEventListeners();
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

                        $(self.$messageContainer).html('');
                        $(self.$mediaManagerFiles.$filePopupFooter).find('.btn').prop('disabled', true);

                        $.ajax({
                            url: self.$mediaManagerFiles.$connectorUrl,
                            method: 'post',
                            data: {
                                action       : 'mgr/files',
                                method       : 'crop',
                                HTTP_MODAUTH : self.$mediaManagerFiles.$httpModAuth,
                                fileId       : self.$mediaManagerFiles.$currentFile,
                                cropData     : result.toDataURL(),
                                isNewImage   : data.newImage
                            }
                        }).success(function(data) {
                            if (data.results.status === 'success') {
                                self.removeEventListeners();

                                if (data.results.id) {
                                    self.$mediaManagerFiles.$currentFile = data.results.id;
                                }

                                self.$mediaManagerFiles.filePopup();
                                self.$mediaManagerFiles.getList();

                                return false;
                            }

                            $(self.$messageContainer).html(data.results.message);
                            $(self.$mediaManagerFiles.$filePopupFooter).find('.btn').prop('disabled', false);
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

    setEventListeners: function() {
        var self = this;

        $(self.$buttons).on('click', '[data-method]', function(event) {
            self.buttons(event);
        });

        $(self.$mediaManagerFiles.$filePopupFooter).on('click', '[data-method]', function(event) {
            self.buttons(event);
        });

        $(self.$mediaManagerFiles.$filePopupFooter).on('click', 'button[data-file-action-button]', function(event) {
            MediaManagerFilesCropper.removeEventListeners();
        });

        $(document).on('keyup', self.$dataX, function(e) {
            self.setData(e, 'x');
        });

        $(document).on('keyup', self.$dataY, function(e) {
            self.setData(e, 'y');
        });

        $(document).on('keyup', self.$dataWidth, function(e) {
            self.setData(e, 'width');
        });

        $(document).on('keyup', self.$dataHeight, function(e) {
            self.setData(e, 'height');
        });

        $(document).on('keyup', self.$dataRotate, function(e) {
            self.setData(e, 'rotate');
        });
    },

    removeEventListeners: function() {
        var self = this;

        $(self.$buttons).off('click');
        $(self.$mediaManagerFiles.$filePopupFooter).off('click');
        self.$image.cropper('destroy');
    },

    setData : function(e, option) {
        var self    = this,
            options = {};

        if (e.keyCode === 37 || e.keyCode === 39) {
            return false;
        }

        options[option] = parseInt(e.target.value);

        self.$image.cropper('setData', options);
    }

}

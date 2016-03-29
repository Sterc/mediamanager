+function ($) {

    var MediaManagerFiles = {

        $dropzone              : null,
        $dropzoneForm          : 'form[data-dropzone-form]',

        $uploadMedia           : 'button[data-upload-media]',
        $uploadSelectedFiles   : '.upload-selected-files',

        $selectContext         : 'select[data-select-context]',

        $categoryTree          : 'div[data-category-tree]',

        $advancedSearch        : 'button[data-advanced-search]',
        $advancedSearchFilters : 'div[data-advanced-search-filters]',

        init: function() {
            this.dropzone();
            this.getCategories();
            this.getList();
        },

        dropzone: function() {
            var self = this;

            self.$dropzone = new Dropzone(document.getElementById('mediaManagerDropzone'), {
                maxFilesize: 100,
                maxThumbnailFilesize: 1,
                autoProcessQueue: false,
                dictDefaultMessage: '',
                previewsContainer: '.dropzone-previews',
                params: {
                    action: 'mgr/files',
                    method: 'add'
                },
                init: function() {
                    this.on('addedfile', function(file) {
                        $('.dropzone-actions').show(); // @TODO: Only activate button if categories are linked to media files
                    });
                },
                previewTemplate:
                '<div class="dz-preview dz-file-preview">' +
                    '<div class="row">' +
                        '<div class="col-md-2 col-lg-1">' +
                            '<img data-dz-thumbnail />' +
                        '</div>' +
                        '<div class="col-md-3 col-lg-4">' +
                            '<div class=""><span data-dz-name></span></div>' +
                            '<div class="" data-dz-size></div>' +
                        '</div>' +
                        '<div class="col-sm-4">' +
                            '<div class="categories">' +
                                '<input type="text" name="categories">' +
                            '</div>' +
                            '<div class="tags">' +
                                '<input type="text" name="tags">' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-2">' +
                            '<div class="progress progress-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
                                '<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-1"><button type="button" class="btn btn-danger dz-remove pull-right" data-dz-remove="">Delete</button></div>' +
                    '</div>' +
                    '<div class="alert alert-danger" role="alert"><strong>Warning!</strong><span data-dz-errormessage></span></div>' +
                '</div>'
            });

            self.$dropzone.on('sending', function(file) {
                file.previewElement.querySelector('.btn-danger').setAttribute('disabled', 'disabled');
            });
        },

        dropzoneOpen: function() {
            var self = this;
            $(self.$dropzoneForm).slideToggle();
        },

        dropzoneProcessQueue: function() {
            var self = this;
            self.$dropzone.processQueue();
        },

        advancedSearchOpen: function() {
            var self = this;
            $(self.$advancedSearchFilters).slideToggle();
        },

        getCategories: function() {
            var self = this;
            var tree = [
                {
                    text: "Home"
                },
                {
                    text: "Documents",
                    nodes: [
                        {
                            text: "Brochures"
                        }
                    ]
                },
                {
                    text: "Blog"
                },
                {
                    text: "Archive"
                }
            ];

            $(self.$categoryTree).treeview({
                data: tree,
                levels: 1
            });
        },

        getList: function() {
            var self = this;

            $.ajax({
                url: $(self.$dropzoneForm).attr('action'),
                method: 'post',
                data: {
                    action       : $('input[name="action"]', self.$dropzoneForm).val(),
                    HTTP_MODAUTH : $('input[name="HTTP_MODAUTH"]', self.$dropzoneForm).val(),
                    method       : 'list',
                    context      : self.getContext()
                }
            }).success(function(data) {
                $('.media-container .panel-body').html(data.results.html);
            });
        },

        getContext: function() {
            var context = /context=([^&]+)/.exec(window.location.href);
            if (context === null) {
                return 0;
            }
            return context[1];
        },

        changeContext: function(e) {
            var self = this;
            window.location.href = self.updateQueryStringParameter(window.location.href, 'context', e.target.value);
        },

        updateQueryStringParameter: function (uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        }

    }

    $(document).ready(function() {
        MediaManagerFiles.init();
    });

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'dropzoneOpen')
    }, MediaManagerFiles.$uploadMedia);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'dropzoneProcessQueue')
    }, MediaManagerFiles.$uploadSelectedFiles);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'advancedSearchOpen')
    }, MediaManagerFiles.$advancedSearch);

    $(document).on({
        change : $.proxy(MediaManagerFiles, 'changeContext')
    }, MediaManagerFiles.$selectContext);

}(jQuery);
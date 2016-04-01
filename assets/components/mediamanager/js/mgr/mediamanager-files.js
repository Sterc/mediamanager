+function ($) {

    var MediaManagerFiles = {

        $connectorUrl            : null,
        $httpModAuth             : null,

        $dropzone                : null,
        $dropzoneForm            : 'form[data-dropzone-form]',

        $uploadMedia             : 'button[data-upload-media]',
        $uploadSelectedFiles     : '.upload-selected-files',

        $fileContainer           : '.file',
        $fileCategories          : 'select[data-file-categories]',
        $fileTags                : 'select[data-file-tags]',

        $selectContext           : 'select[data-select-context]',

        $categoryTree            : 'div[data-category-tree]',

        $search                  : 'input[data-search]',
        $advancedSearch          : 'button[data-advanced-search]',
        $advancedSearchFilters   : 'div[data-advanced-search-filters]',

        $selectSorting           : 'select[data-sorting]',

        $filterType              : 'select[data-filter-type]',
        $filterCategories        : 'select[data-filter-categories]',
        $filterTags              : 'select[data-filter-tags]',
        $filterUser              : 'select[data-filter-user]',

        $filterCategoriesOptions : null,
        $filterTagsOptions       : null,

        $currentContext          : 0,
        $currentSearch           : '',
        $currentSorting          : [],
        $currentFilters          : {
            categories: [],
            tags: [],
            type: '',
            user: ''
        },

        init: function() {
            this.$connectorUrl = $(this.$dropzoneForm).attr('action');
            this.$httpModAuth = $('input[name="HTTP_MODAUTH"]', this.$dropzoneForm).val();

            this.setContext();
            this.setDropzone();
            this.setSelect2();
            this.getCategories();
            this.getList();
        },

        setDropzone: function() {
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

                        $(self.$fileCategories).select2(self.$filterCategoriesOptions);
                        $(self.$fileTags).select2(self.$filterTagsOptions);
                    });

                    this.on('complete', function(file) {
                        this.removeFile(file);
                    });

                    this.on('queuecomplete', function() {
                        $('.dropzone-actions').hide();
                        self.dropzoneOpen();
                        self.getList();
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
                                '<select name="categories[]" class="form-control" multiple="multiple" data-placeholder="Categories" data-file-categories></select>' +
                            '</div>' +
                            '<div class="tags">' +
                                '<select name="tags[]" class="form-control" multiple="multiple" data-placeholder="Tags" data-file-tags></select>' +
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

        setSelect2: function() {
            var self = this;

            self.$filterCategoriesOptions = {
                ajax: {
                    url: self.$connectorUrl,
                    dataType: 'json',
                    method: 'post',
                    delay: 250,
                    data: function (params) {
                        return {
                            action: 'mgr/categories',
                            method: 'getCategoriesByName',
                            HTTP_MODAUTH : self.$httpModAuth,
                            search: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            };

            self.$filterTagsOptions = {
                ajax: {
                    url: self.$connectorUrl,
                    dataType: 'json',
                    method: 'post',
                    delay: 250,
                    data: function (params) {
                        return {
                            action: 'mgr/tags',
                            method: 'getTagsByName',
                            HTTP_MODAUTH : self.$httpModAuth,
                            search: params.term
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            };

            $(self.$filterCategories).select2(self.$filterCategoriesOptions);
            $(self.$filterTags).select2(self.$filterTagsOptions);

            $(self.$filterTags).on('select2:select', function(e) {
                self.$currentFilters.tags.push(e.params.data.id);
                self.getList();
            });

            $(self.$filterCategories).on('select2:select', function(e) {
                self.$currentFilters.categories.push(e.params.data.id);
                self.getList();
            });

            $(self.$filterTags).on('select2:unselect', function(e) {
                for (var key in self.$currentFilters.tags) {
                    if (self.$currentFilters.tags[key] == e.params.data.id) {
                        self.$currentFilters.tags.splice(key, 1);
                        break;
                    }
                }
                self.getList();
            });

            $(self.$filterCategories).on('select2:unselect', function(e) {
                for (var key in self.$currentFilters.categories) {
                    if (self.$currentFilters.categories[key] == e.params.data.id) {
                        self.$currentFilters.categories.splice(key, 1);
                        break;
                    }
                }
                self.getList();
            });
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
                url: self.$connectorUrl,
                method: 'post',
                data: {
                    action       : 'mgr/files',
                    method       : 'list',
                    HTTP_MODAUTH : self.$httpModAuth,
                    context      : self.$currentContext,
                    search       : self.$currentSearch,
                    filters      : self.$currentFilters,
                    sorting      : self.$currentSorting
                }
            }).success(function(data) {
                $('.media-container .panel-body').html(data.results);
                self.resizeFileContainer();
            });
        },

        setContext: function() {
            var self = this,
                context = /context=([^&]+)/.exec(window.location.href);

            if (context === null) {
                return;
            }

            return self.$currentContext = context[1];
        },

        changeContext: function(e) {
            var self = this;
            window.location.href = self.updateQueryStringParameter(window.location.href, 'context', e.target.value);
        },

        updateQueryStringParameter: function (uri, key, value) {
            var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
            var separator = uri.indexOf('?') !== -1 ? '&' : '?';
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + '=' + value + '$2');
            } else {
                return uri + separator + key + '=' + value;
            }
        },

        changeSorting: function(e) {
            var self = this,
                option = $(e.target).find(':selected');

            self.$currentSorting = [
                option.data('sort-field'),
                option.data('sort-direction')
            ];

            self.getList();
        },

        changeSearch: function(e) {
            var self = this,
                search = e.target.value;

            if ((search.length > 2 && self.$currentSearch !== search) || (self.$currentSearch.length > 2 && search.length == 0)) {
                self.$currentSearch = search;
                self.getList();
            }
        },

        changeFilter: function(e) {
            var self = this;

            switch (Object.keys(e.target.dataset)[0]) {
                case 'filterUser' :
                    self.$currentFilters.user = e.target.value;
                    break;

                case 'filterType' :
                    self.$currentFilters.type = e.target.value;
                    break;

                default :
                    return false;
            }

            self.getList();
        },

        resizeFileContainer: function() {
            var self = this,
                width = $(self.$fileContainer).width();

            $(self.$fileContainer).height(width);
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

    $(document).on({
        keyup : $.proxy(MediaManagerFiles, 'changeSearch')
    }, MediaManagerFiles.$search);

    $(document).on({
        change : $.proxy(MediaManagerFiles, 'changeSorting')
    }, MediaManagerFiles.$selectSorting);

    $(document).on({
        change : $.proxy(MediaManagerFiles, 'changeFilter')
    }, MediaManagerFiles.$filterUser);

    $(document).on({
        change : $.proxy(MediaManagerFiles, 'changeFilter')
    }, MediaManagerFiles.$filterType);

    $(window).on({
        resize : $.proxy(MediaManagerFiles, 'resizeFileContainer')
    }, window);

}(jQuery);


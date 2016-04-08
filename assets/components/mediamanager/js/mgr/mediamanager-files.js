+function ($) {

    var MediaManagerFiles = {

        $connectorUrl            : null,
        $httpModAuth             : null,

        $dropzone                : null,
        $dropzoneForm            : 'form[data-dropzone-form]',
        $dropzoneActions         : '.dropzone-actions',

        $uploadMedia             : 'button[data-upload-media]',
        $uploadSelectedFiles     : '.upload-selected-files',

        $filesContainer          : 'div[data-files]',
        $fileContainer           : '.file',
        $fileCategories          : 'select[data-file-categories]',
        $fileTags                : 'select[data-file-tags]',
        $fileRemoveButton        : 'button[data-dz-remove]',
        $fileErrorMessage        : 'span[data-dz-errormessage]',

        $filePopup               : 'div[data-file-popup]',
        $filePopupButton         : 'button[data-file-popup-button]',
        $fileEditButton          : 'button[data-file-edit-button]',
        $fileCropButton          : 'button[data-file-crop-button]',
        $fileMoveButton          : 'button[data-file-move-button]',
        $fileArchiveButton       : 'button[data-file-archive-button]',
        $fileShareButton         : 'button[data-file-share-button]',
        $fileDeleteButton        : 'button[data-file-delete-button]',

        $selectContext           : 'select[data-select-context]',
        $categoryTree            : 'div[data-category-tree]',

        $bulkActions             : '.bulk-actions',
        $bulkMoveButton          : 'button[data-bulk-move]',
        $bulkArchiveButton       : 'button[data-bulk-archive]',
        $bulkShareButton         : 'button[data-bulk-share]',
        $bulkDownloadButton      : 'button[data-bulk-download]',
        $bulkDeleteButton        : 'button[data-bulk-delete]',
        $bulkCancelButton        : 'button[data-bulk-cancel]',

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
        $categoriesSelectOptions : null,

        $viewMode                : 'span[data-view-mode]',
        $currentViewMode         : 'grid',

        $currentContext          : 0,
        $currentCategory         : 0,
        $currentSearch           : '',
        $currentSorting          : [],
        $currentFilters          : {
            categories: [],
            tags: [],
            type: '',
            user: ''
        },

        $currentFile             : 0,
        $selectedFiles           : [],

        $modxHeader              : '#modx-header',
        $modxContent             : '#modx-content',

        init: function() {
            this.$connectorUrl = $(this.$dropzoneForm).attr('action');
            this.$httpModAuth = $('input[name="HTTP_MODAUTH"]', this.$dropzoneForm).val();

            this.setContext();
            this.setDropzone();
            this.setSelect2();
            this.setPopup();
            this.getCategories();
            this.getList();
        },

        setDropzone: function() {
            var self = this;

            self.$dropzone = new Dropzone(document.getElementById('mediaManagerDropzone'), {
                parallelUploads: 9999,
                maxFiles: 9999,
                maxFilesize: 100,
                maxThumbnailFilesize: 1,
                autoProcessQueue: false,
                clickable: '.clickable',
                dictDefaultMessage: '',
                previewsContainer: '.dropzone-previews',
                params: {
                    action: 'mgr/files',
                    method: 'add'
                },
                init: function() {
                    this.on('addedfile', function(file) {
                        $(self.$dropzoneActions).show();
                        $(self.$fileCategories).select2(self.$filterCategoriesOptions);
                        $(self.$fileTags).select2(self.$filterTagsOptions);
                    });

                    this.on('removedfile', function(file) {
                        var queue = this.getQueuedFiles();

                        if (queue.length === 0) {
                            $(self.$dropzoneActions).hide();
                        }
                    });

                    this.on('sending', function(file) {
                        var $file = $(file.previewElement);

                        $(self.$fileCategories, $file).attr('disabled', 'disabled');
                        $(self.$fileTags, $file).attr('disabled', 'disabled');
                        $(self.$fileRemoveButton, $file).attr('disabled', 'disabled');
                    });

                    this.on('complete', function(file) {
                        var response = JSON.parse(file.xhr.response);
                        $(file.previewElement).delay(1000).html(response.message);
                    });

                    this.on('queuecomplete', function() {
                        $(self.$dropzoneActions).hide();
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
                            '<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
                                '<div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-1"><button type="button" class="btn btn-danger dz-remove pull-right" data-dz-remove="">Delete</button></div>' +
                    '</div>' +
                    '<span data-dz-errormessage></span>' +
                '</div>'
            });
        },

        dropzoneOpen: function() {
            var self = this;
            self.$dropzone.removeAllFiles();
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

        setPopup: function() {
            var self = this;

            $(self.$filePopup).modal({
                show: false,
                keyboard: false,
                backdrop: 'static'
            });

            $(self.$filePopup).on('show.bs.modal', function(e) {
                //var modal = $(this);
                console.log('open');
            });

            $(self.$filePopup).on('hide.bs.modal', function(e) {
                // Reset current file
                self.$currentFile = 0;
            });
        },

        advancedSearchOpen: function() {
            var self = this;
            $(self.$advancedSearchFilters).slideToggle();
        },

        getCategories: function() {
            var self = this;

            $.ajax({
                url: self.$connectorUrl,
                method: 'post',
                data: {
                    action       : 'mgr/categories',
                    method       : 'getTree',
                    HTTP_MODAUTH : self.$httpModAuth
                }
            }).success(function(data) {
                self.$categoriesSelectOptions = data.results.select;
                $(self.$categoryTree).treeview({
                    data: data.results.list,
                    levels: 1,
                    onNodeSelected: function(event, data) {
                        self.$currentCategory = data.categoryId;
                        self.getList();
                    }
                });
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
                    category     : self.$currentCategory,
                    search       : self.$currentSearch,
                    filters      : self.$currentFilters,
                    sorting      : self.$currentSorting,
                    viewMode     : self.$currentViewMode
                }
            }).success(function(data) {
                $(self.$filesContainer).html(data.results);
                self.resizeFileContainer();
                self.setModxContentHeight();
                self.lazyload();
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
            var self = this;

            if (self.$currentViewMode === 'list') {
                return false;
            }

            var width = $(self.$fileContainer).width();
            $(self.$fileContainer).height(width);
        },

        /**
         * Initialize lazyload for images.
         */
        lazyload: function() {
            $('img.lazy').lazyload({
                threshold: 200
            });
        },

        /**
         * Switch between grid and view mode.
         *
         * @param e
         */
        switchViewMode: function(e) {
            var self = this,
                viewMode = e.target.dataset.viewMode;

            $(self.$filesContainer).removeClass('view-mode-' + self.$currentViewMode).addClass('view-mode-' + viewMode);
            self.$currentViewMode = viewMode;

            self.getList();
        },

        /**
         * Set content height to enable scroll bar.
         */
        setModxContentHeight: function() {
            var self = this,
                $modxHeader = $(self.$modxHeader),
                $modxContent = $(self.$modxContent),
                height = $(document).height() - $modxHeader.height();

            $('.x-panel-bwrap', $modxContent).hide();
            $modxContent.height(height);
        },

        /**
         * Add or remove from selected files.
         *
         * @param e
         */
        selectFile: function(e) {
            var self = this,
                $target = $(e.target),
                $fileContainer = $target.parents(self.$fileContainer),
                index = self.$selectedFiles.indexOf($fileContainer.data('id'));

            // Don't add file if edit button is clicked
            if (typeof $target.data('file-popup-button') !== 'undefined') {
                return false;
            }

            $fileContainer.toggleClass('file-selected');

            if (index >= 0) {
                self.$selectedFiles.splice(index, 1);
            } else {
                self.$selectedFiles.push($fileContainer.data('id'));
            }

            self.showBulkActions();
        },

        /**
         * Clear all selected files.
         */
        clearSelectedFiles: function() {
            var self = this;

            self.$selectedFiles = [];
            $(self.$fileContainer).removeClass('file-selected');
            self.showBulkActions();
        },

        /**
         * Show or hide bulk actions.
         */
        showBulkActions: function() {
            var self = this;

            if (self.$selectedFiles.length === 0) {
                $(self.$bulkActions).hide();
            } else {
                $(self.$bulkActions).show();
            }
        },

        /**
         * Move files.
         *
         * @param e
         */
        moveFiles: function(e) {
            var self = this,
                files = self.$selectedFiles;

            console.log('moveFile click');

            if (self.$currentFile !== 0) {
                files = self.$currentFile;
            }

            $('<div />').html($('<select />').addClass('form-control').append(self.$categoriesSelectOptions)).dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.moveTitle,
                buttons : [{
                    text: e.target.dataset.moveConfirm,
                    class: 'btn btn-primary',
                    click: function () {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'move',
                                HTTP_MODAUTH : self.$httpModAuth,
                                files        : files
                            },
                            success: function(data) {
                                //$(self.$listing).html(data.results.html);
                                console.log('moved files');
                            }
                        });

                        $(this).dialog('close');
                    }
                }, {
                    text: e.target.dataset.moveCancel,
                    class: 'btn btn-default',
                    click: function () {
                        $(this).dialog('close');
                    }
                }],
                open: function(event, ui) {
                    $('.ui-dialog-titlebar-close', ui.dialog | ui).hide();
                },
                close : function() {
                    $(this).dialog('destroy').remove();
                }
            });
        },

        /**
         * Archive files.
         *
         * @param e
         */
        archiveFiles: function(e) {
            var self = this,
                files = self.$selectedFiles;

            console.log('moveFile click');

            if (self.$currentFile !== 0) {
                files = self.$currentFile;
            }

            $('<div />').text('Are you sure you want to archive this file?').dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.archiveTitle,
                buttons : [{
                    text: e.target.dataset.archiveConfirm,
                    class: 'btn btn-warning',
                    click: function () {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'archive',
                                HTTP_MODAUTH : self.$httpModAuth,
                                files        : files
                            },
                            success: function(data) {
                                //$(self.$listing).html(data.results.html);
                                console.log('moved files');
                            }
                        });

                        $(this).dialog('close');
                    }
                }, {
                    text: e.target.dataset.archiveCancel,
                    class: 'btn btn-default',
                    click: function () {
                        $(this).dialog('close');
                    }
                }],
                open: function(event, ui) {
                    $('.ui-dialog-titlebar-close', ui.dialog | ui).hide();
                },
                close : function() {
                    $(this).dialog('destroy').remove();
                }
            });
        },

        /**
         * Set file id that we want to edit.
         *
         * @param e
         */
        filePopup: function(e) {
            var self = this;

            self.$currentFile = $(e.target).parents('.file').data('id');
        }

    }

    $(document).ready(function() {
        MediaManagerFiles.init();

        $(window).resize(function() {
            MediaManagerFiles.resizeFileContainer();
            MediaManagerFiles.setModxContentHeight();
        });
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

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'switchViewMode')
    }, MediaManagerFiles.$viewMode);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'selectFile')
    }, MediaManagerFiles.$fileContainer);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'filePopup')
    }, MediaManagerFiles.$filePopupButton);

    // File actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'moveFiles')
    }, MediaManagerFiles.$fileMoveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveFiles')
    }, MediaManagerFiles.$fileArchiveButton);

    // Bulk actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'moveFiles')
    }, MediaManagerFiles.$bulkMoveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveFiles')
    }, MediaManagerFiles.$bulkArchiveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'clearSelectedFiles')
    }, MediaManagerFiles.$bulkCancelButton);

}(jQuery);


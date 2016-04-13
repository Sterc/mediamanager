+function ($) {

    var MediaManagerFiles = {

        $connectorUrl            : null,
        $httpModAuth             : null,

        $dropzone                : null,
        $dropzoneForm            : 'form[data-dropzone-form]',
        $dropzoneFileTemplate    : 'div[data-dropzone-file-template]',
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
        $filePopupBody           : 'div[data-file-popup-body]',
        $filePopupFooter         : 'div[data-file-popup-footer]',
        $filePopupButton         : 'button[data-file-popup-button]',
        $fileActionButton        : 'button[data-file-action-button]',
        $fileMoveButton          : 'button[data-file-move-button]',
        $fileArchiveButton       : 'button[data-file-archive-button]',
        $fileShareButton         : 'button[data-file-share-button]',
        $fileDeleteButton        : 'button[data-file-delete-button]',
        $fileCrop                : 'img.crop',

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

        /**
         * Init
         */
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

        /**
         * Initialize dropzone form.
         */
        setDropzone: function() {
            var self = this;

            self.$dropzone = new Dropzone(document.getElementById('mediaManagerDropzone'), {
                parallelUploads: 9999,
                maxFiles: 9999,
                maxFilesize: 100,
                maxThumbnailFilesize: 10,
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

                    this.on('sending', function(file, xhr, formData) {
                        var $file       = $(file.previewElement),
                            $categories = $(self.$fileCategories, $file),
                            $tags       = $(self.$fileTags, $file),
                            $button     = $(self.$fileRemoveButton, $file);

                        formData.append('categories', $categories.val());
                        formData.append('tags', $tags.val());

                        $categories.attr('disabled', 'disabled');
                        $tags.attr('disabled', 'disabled');
                        $button.attr('disabled', 'disabled');
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
                previewTemplate: $(self.$dropzoneFileTemplate).html()
            });
        },

        /**
         * Open or close dropzone form.
         */
        dropzoneOpen: function() {
            var self = this;
            $(self.$dropzoneForm).slideToggle(400, function() {
                self.$dropzone.removeAllFiles();
            });
        },

        /**
         * Process file queue.
         */
        dropzoneProcessQueue: function() {
            var self = this;
            self.$dropzone.processQueue();
        },

        /**
         * Initialize select2.
         */
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
                minimumInputLength: 1,
                theme: 'default select2-container--categories'
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
                minimumInputLength: 1,
                theme: 'default select2-container--tags'
            };

            $(self.$filterCategories).select2(self.$filterCategoriesOptions);
            $(self.$filterTags).select2(self.$filterTagsOptions);

            // Add tag to filter
            $(self.$filterTags).on('select2:select', function(e) {
                self.$currentFilters.tags.push(e.params.data.id);
                self.getList();
            });

            // Add category to filter
            $(self.$filterCategories).on('select2:select', function(e) {
                self.$currentFilters.categories.push(e.params.data.id);
                self.getList();
            });

            // Remove tag from filter
            $(self.$filterTags).on('select2:unselect', function(e) {
                for (var key in self.$currentFilters.tags) {
                    if (self.$currentFilters.tags[key] == e.params.data.id) {
                        self.$currentFilters.tags.splice(key, 1);
                        break;
                    }
                }
                self.getList();
            });

            // Remove category from filter
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

        /**
         * Initialize file popup.
         */
        setPopup: function() {
            var self = this;

            $(self.$filePopup).modal({
                show: false,
                keyboard: false,
                backdrop: 'static'
            });

            // Set current file
            $(self.$filePopup).on('show.bs.modal', function(e) {
                self.$currentFile = $(e.relatedTarget).parents(self.$fileContainer).data('id');
            });

            // Reset current file
            $(self.$filePopup).on('hide.bs.modal', function(e) {
                self.$currentFile = 0;
            });
        },

        /**
         * Open or close advanced search options.
         */
        advancedSearchOpen: function() {
            var self = this;
            $(self.$advancedSearchFilters).slideToggle();
        },

        /**
         * Get category tree.
         */
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

        /**
         * Get media files.
         */
        getList: function() {
            var self = this;

            $.ajax({
                url: self.$connectorUrl,
                method: 'post',
                data: {
                    action        : 'mgr/files',
                    method        : 'list',
                    HTTP_MODAUTH  : self.$httpModAuth,
                    context       : self.$currentContext,
                    category      : self.$currentCategory,
                    search        : self.$currentSearch,
                    filters       : self.$currentFilters,
                    sorting       : self.$currentSorting,
                    viewMode      : self.$currentViewMode,
                    selectedFiles : self.$selectedFiles
                }
            }).success(function(data) {
                $(self.$filesContainer).html(data.results);
                self.resizeFileContainer();
                self.setModxContentHeight();
                self.lazyload();
            });
        },

        /**
         * Set context.
         *
         * @returns {*}
         */
        setContext: function() {
            var self = this,
                context = /context=([^&]+)/.exec(window.location.href);

            if (context === null) {
                return;
            }

            return self.$currentContext = context[1];
        },

        /**
         * Update context.
         *
         * @param e
         */
        changeContext: function(e) {
            var self = this;
            window.location.href = self.updateQueryStringParameter(window.location.href, 'context', e.target.value);
        },

        /**
         * Update parameter value by key.
         *
         * @param uri
         * @param key
         * @param value
         *
         * @returns {*}
         */
        updateQueryStringParameter: function (uri, key, value) {
            var re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
            var separator = uri.indexOf('?') !== -1 ? '&' : '?';
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + '=' + value + '$2');
            } else {
                return uri + separator + key + '=' + value;
            }
        },

        /**
         * Sort media files.
         *
         * @param e
         */
        changeSorting: function(e) {
            var self = this,
                option = $(e.target).find(':selected');

            self.$currentSorting = [
                option.data('sort-field'),
                option.data('sort-direction')
            ];

            self.getList();
        },

        /**
         * Search media files.
         *
         * @param e
         */
        changeSearch: function(e) {
            var self = this,
                search = e.target.value;

            if ((search.length > 2 && self.$currentSearch !== search) || (self.$currentSearch.length > 2 && search.length == 0)) {
                self.$currentSearch = search;
                self.getList();
            }
        },

        /**
         * Set filters and reload media files.
         *
         * @param e
         * @returns {boolean}
         */
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

        /**
         * Set file container height.
         */
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
            var self = this;

            $('img.lazy').lazyload({
                threshold: 200,
                container: $(self.$modxContent)
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
                fileId = $fileContainer.data('id');

            // Don't add file if edit button is clicked
            if (typeof $target.data('file-popup-button') !== 'undefined') {
                return false;
            }

            $fileContainer.toggleClass('file-selected');

            var index = -1;
            $.each(self.$selectedFiles, function(i) {
                if (self.$selectedFiles[i]['id'] == fileId) {
                    index = i;
                    return false;
                }
            });

            if (index >= 0) {
                self.$selectedFiles.splice(index, 1);
            } else {
                self.$selectedFiles.push({
                    id: fileId,
                    category: self.$currentCategory
                });
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
            var self = this;

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
                                files        : self.$selectedFiles,
                                category     : $(this).find('select').val()
                            },
                            success: function(data) {
                                self.clearSelectedFiles();
                                self.getList();
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

            if (self.$currentFile !== 0) {
                files = self.$currentFile;
            }

            var $dialog = $('<div />').html('<span data-error></span>' + e.target.dataset.archiveMessage).dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.archiveTitle,
                buttons : [{
                    text: e.target.dataset.archiveConfirm,
                    class: 'btn btn-danger',
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
                                // Deselect files
                                if (data.results.archivedFiles.length) {
                                    $.each(data.results.archivedFiles, function (i) {
                                        self.$selectedFiles.splice(i, 1);
                                    });

                                    self.showBulkActions();
                                    self.getList();
                                }

                                if (data.results.status === 'error') {
                                    $dialog.find('span[data-error]').html(data.results.message);
                                    return false;
                                }

                                $dialog.dialog('close');
                            }
                        });
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
         * Share files.
         *
         * @param e
         */
        shareFiles: function(e) {
            var self = this,
                files = self.$selectedFiles;

            if (self.$currentFile !== 0) {
                files = self.$currentFile;
            }

            var $dialog = $('<div />').html('<span data-error></span>' + e.target.dataset.shareMessage).dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.shareTitle,
                buttons : [{
                    text: e.target.dataset.shareConfirm,
                    class: 'btn btn-primary',
                    click: function () {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'share',
                                HTTP_MODAUTH : self.$httpModAuth,
                                files        : files
                            },
                            success: function(data) {
                                if (data.results.status === 'success') {
                                    $dialog.html(data.results.message);
                                    $dialog.next().find('.btn-primary').hide()
                                    self.clearSelectedFiles();
                                } else {
                                    $dialog.find('span[data-error]').html(data.results.message);
                                }
                            }
                        });
                    }
                }, {
                    text: e.target.dataset.shareCancel,
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
         * Open file popup.
         *
         * @param e
         */
        filePopup: function(e) {
            var self = this,
                template = e.target.dataset.template;

            if (typeof template === 'undefined') {
                template = 'preview';
            }

            $.ajax ({
                type: 'POST',
                url: self.$connectorUrl,
                data: {
                    action       : 'mgr/files',
                    method       : 'file',
                    HTTP_MODAUTH : self.$httpModAuth,
                    id           : self.$currentFile,
                    template     : template
                },
                success: function(data) {
                    var $body   = $(self.$filePopupBody),
                        $footer = $(self.$filePopupFooter);

                    $body.html(data.results.body);
                    $footer.html(data.results.footer);

                    var $categories = $(self.$fileCategories, $body).select2(self.$filterCategoriesOptions);
                    var $tags       = $(self.$fileTags, $body).select2(self.$filterTagsOptions);

                    // Add category to file
                    $categories.on('select2:select', function(e) {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'addCategory',
                                HTTP_MODAUTH : self.$httpModAuth,
                                fileId       : self.$currentFile,
                                categoryId   : e.params.data.id
                            }
                        });
                    });

                    // Remove category from file
                    $categories.on('select2:unselect', function(e) {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'removeCategory',
                                HTTP_MODAUTH : self.$httpModAuth,
                                fileId       : self.$currentFile,
                                categoryId   : e.params.data.id
                            }
                        });
                    });

                    // Add tag to file
                    $tags.on('select2:select', function(e) {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'addTag',
                                HTTP_MODAUTH : self.$httpModAuth,
                                fileId       : self.$currentFile,
                                tagId        : e.params.data.id
                            }
                        });
                    });

                    // Remove tag from file
                    $tags.on('select2:unselect', function(e) {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'removeTag',
                                HTTP_MODAUTH : self.$httpModAuth,
                                fileId       : self.$currentFile,
                                tagId        : e.params.data.id
                            }
                        });
                    });

                    $(self.$fileCrop, $body).cropper({
                        dragMode: 'move'
                    });
                }
            });
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

    // File popup actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'filePopup')
    }, MediaManagerFiles.$filePopupButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'filePopup')
    }, MediaManagerFiles.$fileActionButton);

    // File actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveFiles')
    }, MediaManagerFiles.$fileArchiveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'shareFiles')
    }, MediaManagerFiles.$fileShareButton);

    // Bulk actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'moveFiles')
    }, MediaManagerFiles.$bulkMoveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveFiles')
    }, MediaManagerFiles.$bulkArchiveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'shareFiles')
    }, MediaManagerFiles.$bulkShareButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'clearSelectedFiles')
    }, MediaManagerFiles.$bulkCancelButton);

}(jQuery);


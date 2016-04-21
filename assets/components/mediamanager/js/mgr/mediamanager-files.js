+function ($) {

    var MediaManagerFiles = {

        $connectorUrl            : null,
        $httpModAuth             : null,

        $dropzone                : null,
        $dropzoneForm            : 'form[data-dropzone-form]',
        $dropzoneFileTemplate    : 'div[data-dropzone-file-template]',
        $dropzonePreviews        : '.dropzone-previews',
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
        $filterDateFrom          : 'input[data-filter-date-from]',
        $filterDateTo            : 'input[data-filter-date-to]',

        $filterCategoriesOptions : null,
        $filterTagsOptions       : null,
        $categoriesSelectOptions : null,

        $viewMode                : 'i[data-view-mode]',
        $currentViewMode         : 'grid',

        $currentContext          : 0,
        $currentCategory         : 0,
        $currentSearch           : '',
        $currentSorting          : [],
        $currentFilters          : {
            categories: [],
            tags: [],
            type: '',
            user: '',
            date: {
                from: '',
                to: ''
            }
        },

        $currentFile             : 0,
        $selectedFiles           : [],

        $modxHeader              : '#modx-header',
        $modxContent             : '#modx-content',

        $filesCategories         : [],
        $filesTags               : [],

        $filesCropper            : null,

        /**
         * Init
         */
        init: function() {
            var self = this;

            self.$connectorUrl = $(self.$dropzoneForm).attr('action');
            self.$httpModAuth = $('input[name="HTTP_MODAUTH"]', self.$dropzoneForm).val();

            self.$filesCropper = MediaManagerFilesCropper;

            self.setContext();
            self.setDropzone();
            self.setFilters();
            self.setPopup();
            self.getCategories();
            self.getList();
        },

        /**
         * Initialize dropzone form.
         */
        setDropzone: function() {
            var self = this;

            self.$dropzone = new Dropzone(document.getElementById('mediaManagerDropzone'), {
                parallelUploads: 9999,
                maxFiles: 9999,
                maxFilesize: mediaManagerOptions.maxFileSize,
                maxThumbnailFilesize: 10,
                autoProcessQueue: false,
                clickable: '.clickable',
                dictDefaultMessage: '',
                previewsContainer: self.$dropzonePreviews,
                params: {
                    action: 'mgr/files',
                    method: 'add'
                },
                init: function() {
                    var totalFiles = 0,
                        $copyButton = null;

                    this.on('addedfile', function(file) {
                        var $filePreview = $(file.previewElement);

                        if (totalFiles === 0) {
                            $filePreview.find('.tags').append(
                                $copyButton = $('<button />')
                                    .text('Use categories and tags above for all files')
                                    .addClass('btn btn-primary')
                                    .on('click', function(e) {
                                        e.preventDefault();
                                        self.copyCategoriesAndTags();
                                        return false;
                                    })
                                    .hide()
                            );

                            // Disable upload media button
                            $(self.$uploadMedia).prop('disabled', true);
                        }

                        self.$filesCategories[totalFiles] = $(self.$fileCategories, $filePreview).select2(self.$filterCategoriesOptions)
                            .on('select2:select select2:unselect', self.checkCategoriesAndTags);
                        self.$filesTags[totalFiles] = $(self.$fileTags, $filePreview).select2(self.$filterTagsOptions)
                            .on('select2:select select2:unselect', self.checkCategoriesAndTags);

                        // Show upload selected files button
                        $(self.$dropzoneActions).show();

                        // Show copy categories and tags button if more than one file is added
                        if (totalFiles > 0) {
                            $copyButton.show();
                        }

                        ++totalFiles;
                    });

                    this.on('removedfile', function(file) {
                        var queue = this.getQueuedFiles();

                        if (queue.length === 0) {
                            totalFiles = 0;

                            // Enable upload media button
                            $(self.$uploadMedia).prop('disabled', false);

                            // Disable and hide upload selected files button
                            $(self.$uploadSelectedFiles).prop('disabled', true);
                            $(self.$dropzoneActions).hide();
                        }
                    });

                    this.on('sending', function(file, xhr, formData) {
                        var $file       = $(file.previewElement),
                            $categories = $(self.$fileCategories, $file),
                            $tags       = $(self.$fileTags, $file),
                            $button     = $(self.$fileRemoveButton, $file);

                        // Set correct categories and tags for file
                        formData.append('categories', $categories.val());
                        formData.append('tags', $tags.val());

                        // Disable input fields and buttons while file is being uploaded
                        $categories.prop('disabled', true);
                        $tags.prop('disabled', true);
                        $button.prop('disabled', true);
                    });

                    this.on('complete', function(file) {
                        if (typeof file.xhr === 'undefined') {
                            return false;
                        }
                        var response = JSON.parse(file.xhr.response);
                        $(file.previewElement).delay(1500).html(response.message);
                    });

                    this.on('queuecomplete', function() {
                        self.$filesCategories = [];
                        self.$filesTags = [];

                        $(self.$uploadMedia).prop('disabled', false);
                        $(self.$uploadSelectedFiles).prop('disabled', true);
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
         * Check if categories and tags are filled.
         * Enable or disable upload selected files button.
         *
         * @returns {boolean}
         */
        checkCategoriesAndTags: function() {
            var self             = MediaManagerFiles,
                tagsFilled       = true,
                categoriesFilled = true;

            $(self.$fileCategories, $(self.$dropzonePreviews)).each(function() {
                if (this.value === '') {
                    categoriesFilled = false;
                    return false;
                }
            });

            $(self.$fileTags, $(self.$dropzonePreviews)).each(function() {
                if (this.value === '') {
                    tagsFilled = false;
                    return false;
                }
            });

            if (categoriesFilled === false || tagsFilled === false) {
                $(self.$uploadSelectedFiles).prop('disabled', true);
                return false;
            }

            // Enable upload selected files button
            $(self.$uploadSelectedFiles).prop('disabled', false);
            return true;
        },

        /**
         * Copy categories and tags.
         */
        copyCategoriesAndTags: function() {
            var self    = this,
                options = null,
                values  = null;

            $.each(self.$filesCategories, function(i, file) {
                if (i === 0) {
                    options = file.html();
                    values  = file.val();
                    return true;
                }

                file.html(options).val(values).trigger('change');
            });

            $.each(self.$filesTags, function(i, file) {
                if (i === 0) {
                    options = file.html();
                    values  = file.val();
                    return true;
                }

                file.html(options).val(values).trigger('change');
            });

            self.checkCategoriesAndTags();
        },

        /**
         * Initialize advanced search filters.
         */
        setFilters: function() {
            var self = this,
                flag = false;

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

            $(self.$filterDateFrom).datepicker({
                onSelect: function(selectedDate) {
                    self.$currentFilters['date']['from'] = selectedDate;
                    self.getList();
                },
                onClose: function(selectedDate) {
                    $(self.$filterDateTo).datepicker('option', 'minDate', selectedDate);
                },
                dateFormat: 'd M yy',
                changeYear: true,
                maxDate: 0
            }).on('change', function() {
                self.$currentFilters['date']['from'] = '';
                self.getList();
            });

            $(self.$filterDateTo).datepicker({
                onSelect: function (selectedDate) {
                    self.$currentFilters['date']['to'] = selectedDate;
                    self.getList();
                },
                dateFormat: 'd M yy',
                changeYear: true,
                maxDate: 0
            }).on('change', function() {
                self.$currentFilters['date']['to'] = '';
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
                        var currentUrl = window.location.href;
                        var newUrl = self.updateQueryStringParameter(currentUrl,'category',data.categoryId);
                        if(currentUrl != newUrl) {
                            history.pushState({}, '', newUrl);
                        }
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
                $fileCheckbox = $fileContainer.find('input[type="checkbox"]'),
                fileId = $fileContainer.data('id');

            // Don't add file if edit button is clicked
            if (typeof $target.data('file-popup-button') !== 'undefined') {
                return false;
            }

            $fileContainer.toggleClass('file-selected');
            $fileCheckbox.prop('checked', !$fileCheckbox.prop('checked'));

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

                                    $(self.$filePopup).modal('hide');
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
         * Delete file.
         *
         * @param e
         */
        deleteFile: function(e) {
            var self = this;

            var $dialog = $('<div />').html(e.target.dataset.deleteMessage).dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.deleteTitle,
                buttons : [{
                    text: e.target.dataset.deleteConfirm,
                    class: 'btn btn-danger',
                    click: function () {
                        $.ajax({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'delete',
                                HTTP_MODAUTH : self.$httpModAuth,
                                fileId       : self.$currentFile
                            },
                            success: function(data) {
                                $(self.$filePopup).modal('hide');
                                self.clearSelectedFiles();
                                self.showBulkActions();
                                self.getList();

                                $dialog.dialog('close');
                            }
                        });
                    }
                }, {
                    text: e.target.dataset.deleteCancel,
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
                template;

            if (typeof e !== 'undefined') {
                template = e.target.dataset.template;
            }

            if (typeof template === 'undefined') {
                template = 'preview';
            }

            // White loading screen
            $(self.$filePopupBody).html($('<div />').css('width', '100%').css('height', $(self.$filePopupBody).height()));

            // Get new template
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

                    if (template === 'crop') {
                        self.$filesCropper.init($(self.$fileCrop, $body), self);
                    }
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

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'deleteFile')
    }, MediaManagerFiles.$fileDeleteButton);

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
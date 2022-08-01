$.fn.modal.Constructor.prototype.enforceFocus = function () {};

+function ($) {

    var MediaManagerFiles = {

        $connectorUrl            : null,
        $httpModAuth             : null,

        $dropzone                : null,
        $dropzoneForm            : 'form[data-dropzone-form]',
        $dropzoneFileTemplate    : 'div[data-dropzone-file-template]',
        $dropzonePreviews        : 'div[data-dropzone-previews]',
        $dropzoneActions         : 'div[data-dropzone-actions]',
        $dropzoneCopyButton      : 'button[data-copy-values]',
        $dropzoneImageTypes      : ['image/jpg', 'image/png', 'image/gif', 'image/jpeg'],
        $dropzoneFeedback        : 'div[data-dropzone-feedback]',

        $editForm                : 'form[data-edit-form]',

        $alertMessagesContainer  : 'div[data-alert-messages]',
        $uploadMedia             : 'button[data-upload-media]',
        $uploadSelectedFiles     : '.upload-selected-files',

        $addMetaFieldsButton     : 'button[data-add-meta]',
        $removeMetaFieldsButton  : 'button[data-remove-meta]',

        $filesContainer          : 'div[data-files]',
        $fileContainer           : '.file',
        $fileCategories          : 'select[data-file-categories]',
        $fileTags                : 'select[data-file-tags]',
        $fileSourceTags          : 'select[data-file-source-tags]',
        $fileMeta                : 'input[data-file-meta]',
        $fileLicense             : 'input[data-file-license],select[data-file-license]',
        $fileRemoveButton        : 'button[data-dz-remove]',

        $fileHistoryTable        : 'div[data-history-table]',
        $fileHistoryButton       : 'button[data-file-history-button]',

        $filePopup               : 'div[data-file-popup]',
        $filePopupBody           : 'div[data-file-popup-body]',
        $filePopupFooter         : 'div[data-file-popup-footer]',
        $filePopupButton         : 'button[data-file-popup-button]',
        $filePopupFeedback       : 'div[data-file-popup-feedback]',
        $fileRelations           : 'td[data-file-relations]',
        $fileActionButton        : 'button[data-file-action-button]',
        $fileMoveButton          : 'button[data-file-move-button]',
        $fileArchiveButton       : 'button[data-file-archive-button]',
        $fileArchiveReplaceButton: 'button[data-file-archive-replace-button]',
        $fileShareButton         : 'button[data-file-share-button]',
        $fileDeleteButton        : 'button[data-file-delete-button]',
        $fileCopyButton          : 'button[data-file-copy-button]',
        $fileEditSaveButton      : 'button[data-file-edit-save]',
        $fileRevertButton        : 'button[data-revert-button]',
        $fileCrop                : 'img.crop',
        $filePreviewLink         : 'a[data-preview-link]',

        $selectSource            : 'select[data-select-source]',
        $categoryTree            : 'div[data-category-tree]',

        $bulkActions             : '.bulk-actions',
        $bulkMoveButton          : 'button[data-bulk-move]',
        $bulkArchiveButton       : 'button[data-bulk-archive]',
        $bulkUnArchiveButton     : 'button[data-bulk-unarchive]',
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
        $filterDate              : 'select[data-filter-date]',
        $filterDateCustom        : 'div[data-filter-date-custom]',
        $filterDateFrom          : 'input[data-filter-date-from]',
        $filterDateTo            : 'input[data-filter-date-to]',

        $filterCategoriesOptions : null,
        $filterTagsOptions       : null,
        $filterSourceTagsOptions : null,

        $categoriesSelectOptions : null,

        $viewMode                : 'i[data-view-mode]',
        $currentViewMode         : 'grid',
        $currentSource           : 0,
        $currentCategory         : 0,
        $currentSearch           : '',
        $currentSorting          : [],
        $currentFilters          : {
            categories : [],
            tags : [],
            type : '',
            user : '',
            date : {
                from : '',
                to   : ''
            }
        },
        $currentLimit            : 30,
        $currentOffset           : 0,

        $archiveReplaceFileId    : '',
        $archiveCategoryId       : -1,
        $currentFile             : 0,
        $selectedFiles           : [],

        $modxHeader              : '#modx-header',
        $modxContent             : '#modx-content',

        $filesSourceTags         : [],
        $filesCropper            : null,

        $breadcrumbsContainer    : 'ol.breadcrumb',
        $breadcrumbs             : [],
        $breadcrumb              : 'a[data-breadcrumb-id]',

        $options                 : null,
        $pagination              : 'a[data-pagination]',
        $paginationActive        : false,

        /**
         * Init
         */
        init: function() {
            var self = this;

            self.$connectorUrl   = $(self.$dropzoneForm).attr('action');
            self.$httpModAuth    = $('input[name="HTTP_MODAUTH"]', self.$dropzoneForm).val();
            self.$filesCropper   = MediaManagerFilesCropper;
            self.$options        = mediaManagerOptions;

            self.setSource();
            self.setCategory();
            self.setDropzone();
            self.setFilters();
            self.setPopup();
            self.getCategories();
            self.getList();

            var queryString = window.location.search;
            var urlParams   = new URLSearchParams(queryString);
            if (urlParams.get('file')) {
                self.previewById(urlParams.get('file'))
            }
        },

        /**
         * Initialize dropzone form.
         */
        setDropzone: function() {
            var self = this;

            self.$dropzone = new Dropzone(document.getElementById('mediaManagerDropzone'), {
                parallelUploads         : 9999,
                maxFiles                : 9999,
                maxFilesize             : mediaManagerOptions.dropzone.maxFileSize,
                maxThumbnailFilesize    : 10,
                autoProcessQueue        : false,
                clickable               : '[data-dropzone-trigger]',
                dictDefaultMessage      : '',
                previewsContainer       : self.$dropzonePreviews,
                previewTemplate         : $(self.$dropzoneFileTemplate).html(),
                acceptedFiles           : mediaManagerOptions.dropzone.acceptedFiles,
                params                  : {
                    action                  : 'mgr/files',
                    method                  : 'add'
                },
                init: function() {
                    var files               = {};
                    var $dropzoneUploads    = $(self.$uploadMedia);
                    var $dropzoneActions    = $(self.$dropzoneActions);
                    var $dropzoneFeedback   = $(self.$dropzoneFeedback);

                    this.on('addedfile', function (file) {
                        var $file               = $(file.previewElement);
                        var data                = files[file.name] || {};

                        var $fieldCategories    = $(self.$fileCategories, $file).select2(self.$filterCategoriesOptions);
                        var $fieldTags          = $(self.$fileTags, $file).select2(self.$filterTagsOptions);
                        var $fieldsMeta         = $(self.$fileMeta, $file);
                        var $fieldsLicense      = $(self.$fileLicense, $file);

                        if (data[$fieldCategories.attr('name')]) {
                            $fieldCategories.val(data[$fieldCategories.attr('name')]).trigger('change');
                        } else {
                            $fieldCategories.val(self.$currentCategory).trigger('change');
                        }

                        if (data[$fieldTags.attr('name')]) {
                            $fieldTags.val(data[$fieldTags.attr('name')]).trigger('change');
                        }

                        $fieldsMeta.each(function (index, fieldMeta) {
                            var $fieldMeta = $(fieldMeta, $file);

                            if (data[$fieldMeta.attr('name')]) {
                                $fieldMeta.val(data[$fieldMeta.attr('name')]);
                            }
                        });

                        /* Make names of radio buttons unique to prevent issues. */
                        $('.dz-file-preview').each(function (previewIndex, preview) {
                            $('input[type="radio"]', $(preview)).each(function () {
                                var name = $(this).attr('name').replace(/[0-9)]/g, '');
                                name += previewIndex;

                                $(this).attr('name', name);
                            });
                        });

                        $fieldsLicense.each(function (index, fieldLicense) {
                            var $fieldLicense = $(fieldLicense, $file);
                            var cleanedName   = $fieldLicense.attr('name').replace(/[0-9)]/g, '');
                            cleanedName       = 'license[' + cleanedName.replace(/^l\[([a-z_]+)\]$/gi, '$1') + ']';

                            if (data[cleanedName]) {
                                if ($fieldLicense.is(':radio')) {
                                    $('[name="' + $fieldLicense.attr('name') + '"]', $file).each(function (index, radioItem) {
                                        if ($(radioItem).val() == data[cleanedName]) {
                                            $(radioItem).attr('checked', true);
                                        } else {
                                            $(radioItem).attr('checked', false);
                                        }
                                    });
                                } else {
                                    $fieldLicense.val(data[cleanedName]);
                                }
                            }
                        });

                        self.renderErrors($file, file.errors);

                        if (this.files.length >= 1) {
                            $dropzoneUploads.prop('disabled', true);
                            $dropzoneActions.show();
                        } else {
                            $dropzoneUploads.prop('disabled', false);
                            $dropzoneActions.hide();
                        }

                        self.onToggleCopyButton(this.files.length);
                    });

                    this.on('removedfile', function (file) {
                        if (!file.cache) {
                            files[file.name] = {};
                        }

                        if (this.files.length >= 1) {
                            $dropzoneUploads.prop('disabled', true);

                            $dropzoneActions.show();
                        } else {
                            $dropzoneUploads.prop('disabled', false);

                            $dropzoneActions.hide();
                        }

                        self.onToggleCopyButton(this.files.length);
                    });

                    this.on('sending', function(file, xhr, formData) {
                        var $file               = $(file.previewElement);
                        var data = {};

                        var $fieldCategories    = $(self.$fileCategories, $file);
                        var $fieldTags          = $(self.$fileTags, $file);
                        var $fieldsMeta         = $(self.$fileMeta, $file);
                        var $fieldsLicense      = $(self.$fileLicense, $file);

                        $fieldCategories.prop('disabled', true);
                        data[$fieldCategories.attr('name')] = $fieldCategories.val();

                        $fieldTags.prop('disabled', true);
                        data[$fieldTags.attr('name')] = $fieldTags.val();

                        formData.append('categories', $fieldCategories.val());
                        formData.append('tags', $fieldTags.val());

                        $fieldsMeta.each(function (index, fieldMeta) {
                            var $fieldMeta = $(fieldMeta);

                            $fieldMeta.prop('disabled', true);
                            data[$fieldMeta.attr('name')] = $fieldMeta.val();

                            formData.append('meta[' + $fieldMeta.attr('name').replace(/^m\[([a-z]+)\]$/gi, '$1') + ']', $fieldMeta.val());
                        });

                        $fieldsLicense.each(function (index, fieldLicense) {
                            var $fieldLicense = $(fieldLicense);
                            var append = false;

                            $fieldLicense.prop('disabled', true);

                            if (!$fieldLicense.is(':radio') || ($fieldLicense.is(':radio') && $fieldLicense.is(':checked'))) {
                                append = true;
                            }

                            if (append) {
                                var cleanedName = $fieldLicense.attr('name').replace(/[0-9)]/g, '');
                                cleanedName         = 'license[' + cleanedName.replace(/^l\[([a-z_]+)\]$/gi, '$1') + ']';

                                if (!$fieldLicense.is(':file')) {
                                    formData.append(cleanedName, $fieldLicense.val());
                                    data[cleanedName] = $fieldLicense.val();
                                } else {
                                    if ($fieldLicense.get(0).files[0]) {
                                        formData.set('license_file', $fieldLicense.get(0).files[0]);
                                    }
                                }
                            }
                        });

                        $(self.$fileRemoveButton, $file).prop('disabled', true);

                        files[file.name] = data;
                    });

                    this.on('complete', function (file) {
                        var $file   = $(file.previewElement);
                        var success = null;

                        file.errors = null;

                        if (file.xhr.status === 413) {
                            success = false;

                            $dropzoneFeedback.append(
                                $('<div class="alert alert-danger">').append(self.$options.message.maxFileSize)
                            );
                        } else {
                            var response = JSON.parse(file.xhr.response);

                            $dropzoneFeedback.append(response.message);

                            if (response.status === 'success') {
                                success = true;
                            } else {
                                success = false;
                            }

                            if (response.errors) {
                                file.errors = response.errors;
                            }
                        }

                        $(self.$fileCategories, $file).prop('disabled', false);
                        $(self.$fileTags, $file).prop('disabled', false);
                        $(self.$fileMeta, $file).prop('disabled', false);

                        $(self.$fileRemoveButton, $file).prop('disabled', false);

                        if (success) {
                            this.removeFile(file);
                        } else {
                            file.cache = true;

                            this.removeFile(file);
                            this.addFile(file);
                        }

                        self.getList();
                    });

                    this.on('queuecomplete', function() {
                        files = {};

                        $dropzoneUploads.prop('disabled', false);

                        self.getList();
                    });
                }
            });
        },
        /*
         * Open or close history form.
         */
        historyToggle: function() {
            var self = this;
            $(self.$fileHistoryTable).slideToggle(400);
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
            $(this.$dropzoneFeedback).empty();

            this.$dropzone.processQueue();
        },

        /**
         * Copy values from the first file
         */
        onHandleCopyValues: function() {
            var values = {};

            $(this.$fileCategories, $(this.$dropzonePreviews)).each(function (index, category) {
                var $category = $(category);

                if (values[$category.attr('name')] === undefined) {
                    values[$category.attr('name')] = $category.val();
                } else {
                    $category.val(values[$category.attr('name')]).trigger('change');
                }
            });

            $(this.$fileTags, $(this.$dropzonePreviews)).each(function (index, tag) {
                var $tag = $(tag);

                if (values[$tag.attr('name')] === undefined) {
                    values[$tag.attr('name')] = $tag.val();
                } else {
                    $tag.val(values[$tag.attr('name')]).trigger('change');
                }
            });

            $(this.$fileMeta, $(this.$dropzonePreviews)).each(function (index, meta) {
                var $meta = $(meta);

                if (values[$meta.attr('name')] === undefined) {
                    values[$meta.attr('name')] = $meta.val();
                } else {
                    $meta.val(values[$meta.attr('name')]).trigger('change');
                }
            });
        },

        /**
         * Show or hide copy button.s
         *
         * @param files
         */
        onToggleCopyButton: function(files) {
            var self = this;

            if (files > 1) {
                $(self.$dropzoneCopyButton, $(self.$dropzonePreviews)).hide().off('click');

                $(self.$dropzoneCopyButton, $(self.$dropzonePreviews)).first().show().on('click', function() {
                    self.onHandleCopyValues();

                    return false;
                });
            }
        },

        fileRevert: function(e) {
            var self = this;
            var version = e.target.dataset.versionId;

            $.ajax({
                url: self.$connectorUrl,
                method: 'post',
                data: {
                    action        : 'mgr/files',
                    method        : 'revert',
                    HTTP_MODAUTH  : self.$httpModAuth,
                    version       : version
                }
            }).success(function(data) {
                self.filePopup();
            });

        },

        /**
         * Initialize advanced search filters.
         */
        setFilters: function() {
            var self = this;

            self.$filterCategoriesOptions = {
                data: self.$options.categories,
                theme: 'default select2-container--categories'
            };

            self.$filterTagsOptions = {
                data: self.$options.tags,
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
                var id = $(e.relatedTarget).parents(self.$fileContainer).data('id');
                if (typeof id !== 'undefined') {
                    self.$currentFile = id;
                }
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
                    HTTP_MODAUTH : self.$httpModAuth,
                    selected     : self.$currentCategory
                }
            }).success(function(data) {
                self.$categoriesSelectOptions = data.results.select;
                $(self.$categoryTree).treeview({
                    data: data.results.list,
                    levels: 1,
                    onNodeSelected: function(event, data) {
                        var currentUrl = window.location.href,
                            newUrl     = self.updateQueryStringParameter(currentUrl, 'category', data.categoryId);

                        if (currentUrl !== newUrl) {
                            history.pushState({}, '', newUrl);
                        }

                        // Reset selected files when selecting other category
                        self.clearSelectedFiles();

                        self.$currentCategory = data.categoryId;
                        self.getList();
                        var selectedNodes = $(self.$categoryTree).treeview('getSelected');
                        for (var i = 0; i < selectedNodes.length; i++) {
                            if (data.nodeId !== selectedNodes[i].nodeId){
                                $(self.$categoryTree).treeview('unselectNode', [ selectedNodes[i].nodeId, { silent: true } ]);
                            }
                        }
                    },
                    onNodeUnselected: function(event, data) {
                        // prevent node unselected
                        $(self.$categoryTree).treeview('selectNode', [ data.nodeId, { silent: true } ]);
                    }
                });

                var selectedNodes = $(self.$categoryTree).treeview('getSelected');
                $.each(selectedNodes, function(index, value) {
                    $(self.$categoryTree).treeview('revealNode', [value.nodeId, {silent: true}]);
                });
            });
        },

        /**
         * Get media files.
         *
         * @param setViewModeClass
         */
        getList: function(setViewModeClass) {
            var self = this;

            $.ajax({
                url: self.$connectorUrl,
                method: 'post',
                data: {
                    action        : 'mgr/files',
                    method        : 'list',
                    HTTP_MODAUTH  : self.$httpModAuth,
                    category      : self.$currentCategory,
                    search        : self.$currentSearch,
                    filters       : self.$currentFilters,
                    sorting       : self.$currentSorting,
                    viewMode      : self.$currentViewMode,
                    selectedFiles : self.$selectedFiles,
                    limit         : self.$currentLimit,
                    offset        : self.$currentOffset
                }
            }).success(function(data) {
                if (setViewModeClass) {
                    self.setViewModeClass();
                }

                $(self.$filesContainer).html(data.results);

                self.resizeFileContainer();
                self.setModxContentHeight();
                self.setPagination();
            });
        },

        /**
         * Set pagination.
         */
        setPagination : function () {
            var self = this,
                filesContainer = $(self.$filesContainer);

            if (self.$paginationActive) {
                $(filesContainer).jscroll.destroy();
            }

            $(filesContainer).jscroll({
                autoTrigger  : true,
                padding      : 200,
                nextSelector : self.$pagination,
                callback     : function() {
                    self.resizeFileContainer();
                    self.setModxContentHeight();
                }
            });

            self.$paginationActive = true;
        },

        /**
         * Set source.
         *
         * @returns {*}
         */
        setSource: function() {
            var self = this,
                source = /source=([^&]+)/.exec(window.location.href);

            if (source === null) {
                return;
            }

            return self.$currentSource = source[1];
        },

        /**
         * Update source.
         *
         * @param e
         */
        changeSource: function(e) {
            var self     = this,
                location = window.location.href;

            // Reset category
            location = self.updateQueryStringParameter(location, 'category', 0);

            // Change source
            window.location.href = self.updateQueryStringParameter(location, 'source', e.target.value);
        },

        /**
         * Set category based on category url parameter.
         *
         * @returns {*}
         */
        setCategory: function() {
            var self = this,
                category = /category=([^&]+)/.exec(window.location.href);

            if (category === null) {
                return;
            }

            return self.$currentCategory = category[1];
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

            if ((self.$currentSearch !== search) || (search.length == 0)) {
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

                case 'filterDate' :
                    var from = '',
                        to   = '';

                    $(self.$filterDateCustom).find('input').hide();

                    switch (e.target.value) {
                        case 'recent' :
                            from = new Date();
                            from = from.setDate(from.getDate() - 7);
                            break;
                        case 'custom' :
                            $(self.$filterDateCustom).find('input').val('').show();
                            break;
                    }

                    self.$currentFilters['date']['from'] = from;
                    self.$currentFilters['date']['to']   = to;
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
         * Switch between grid and view mode.
         *
         * @param e
         */
        switchViewMode : function (e) {
            var self = this,
                viewMode = e.target.dataset.viewMode;

            self.$currentViewMode = viewMode;

            self.getList(true);
        },

        /**
         * Set view mode class.
         */
        setViewModeClass : function () {
            var self = this;

            if (self.$currentViewMode === 'list') {
                $(self.$filesContainer).removeClass('view-mode-grid').addClass('view-mode-list');
            } else {
                $(self.$filesContainer).removeClass('view-mode-list').addClass('view-mode-grid');
            }
        },

        /**
         * Set content height to enable scroll bar.
         */
        setModxContentHeight: function() {
            var self            = this,
                $modxHeader     = $(self.$modxHeader),
                $modxContent    = $(self.$modxContent),
                majorVersion    = parseInt(MODx.config.version.split('.')[0]),
                height          = majorVersion > 2 ? $(window).height() : $(window).height() - $modxHeader.height();

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

            // Select file to replace
            if (self.$archiveReplaceFileId !== '') {
                var $dialog = $('<div />').html(self.$options.message.replaceConfirm).dialog({
                    draggable: false,
                    resizable: false,
                    modal: true,
                    title: self.$options.message.replaceButton,
                    buttons : [{
                        text: self.$options.message.replaceButton,
                        class: 'btn btn-primary',
                        click: function() {
                            $.ajax ({
                                type: 'POST',
                                url: self.$connectorUrl,
                                data: {
                                    action       : 'mgr/files',
                                    method       : 'archiveReplace',
                                    HTTP_MODAUTH : self.$httpModAuth,
                                    fileId       : self.$archiveReplaceFileId,
                                    newFileId    : fileId
                                },
                                complete: function (data) {
                                    self.$currentFile = fileId;
                                    self.getList();

                                    $dialog.dialog('close');

                                    var alert = '<div class="alert alert-success alert-dismissible fade in move-alert" role="alert">';
                                    alert = alert + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                                    alert = alert + data.responseJSON.results.message;
                                    alert = alert + '</div>';

                                    $(alert).appendTo('[data-alert-messages]');
                                }
                            });
                        }
                    }, {
                        text: self.$options.cancel,
                        class: 'btn btn-default',
                        click: function () {
                            $(this).dialog('close');
                        }
                    }],
                    open: function(event, ui) {
                        $('.ui-dialog-titlebar-close', ui.dialog | ui).hide();
                    },
                    close : function() {
                        self.$archiveReplaceFileId = '';
                        $(self.$alertMessagesContainer).html('');

                        $(this).dialog('destroy').remove();
                    }
                });

                return false;
            }

            // Select/unselect file
            $fileContainer.toggleClass('file-selected');
            if (!$target.is('input')) {
                $fileCheckbox.prop('checked', !$fileCheckbox.prop('checked'));
            }

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
            // Disable / enable buttons in archive
            if (self.$currentCategory == self.$archiveCategoryId) {
                $(self.$bulkActions+' button[data-bulk-move]').addClass('hidden');
                $(self.$bulkActions+' button[data-bulk-archive]').addClass('hidden');
                $(self.$bulkActions+' button[data-bulk-share]').addClass('hidden');
                $(self.$bulkActions+' button[data-bulk-download]').addClass('hidden');
                $(self.$bulkActions+' button[data-bulk-delete]').removeClass('hidden');
                $(self.$bulkActions+' button[data-bulk-unarchive]').removeClass('hidden');
            } else {
                $(self.$bulkActions+' button[data-bulk-move]').removeClass('hidden');
                $(self.$bulkActions+' button[data-bulk-archive]').removeClass('hidden');
                $(self.$bulkActions+' button[data-bulk-share]').removeClass('hidden');
                $(self.$bulkActions+' button[data-bulk-download]').removeClass('hidden');
                $(self.$bulkActions+' button[data-bulk-delete]').addClass('hidden');
                $(self.$bulkActions+' button[data-bulk-unarchive]').addClass('hidden');
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
                                var alert = '<div class="alert alert-success alert-dismissible fade in move-alert" role="alert">';
                                alert = alert + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
                                alert = alert + data.results.message;
                                alert = alert + '</div>';

                                $(alert).insertAfter('.filters');

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
         * Unarchive files.
         *
         * @param e
         */
        unArchiveFiles: function(e) {
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
                                method       : 'unarchive',
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

        archiveReplaceFile: function(e) {
            var self = this;

            $('<div />').html(e.target.dataset.archiveMessage).dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.archiveTitle,
                buttons : [{
                    text: e.target.dataset.archiveConfirm,
                    class: 'btn btn-danger',
                    click: function () {
                        self.$archiveReplaceFileId = self.$currentFile;
                        $(self.$alertMessagesContainer).html(self.alert(e.target.dataset.archiveSelectMessage, 'info'));
                        $(self.$filePopup).modal('hide');
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
                                    $dialog.next().find('.btn-primary').hide();
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
         * Download files.
         *
         * @param e
         */
        downloadFiles: function(e) {
            var self  = this,
                files = self.$selectedFiles;

            if (self.$currentFile !== 0) {
                files = self.$currentFile;
            }

            var $dialog = $('<div />').html('<span data-error></span>' + e.target.dataset.downloadMessage).dialog({
                draggable   : false,
                resizable   : false,
                modal       : true,
                title       : e.target.dataset.downloadTitle,
                buttons     : [{
                    text        : e.target.dataset.downloadConfirm,
                    class       : 'btn btn-primary',
                    click       : function () {
                        $.ajax ({
                            type    : 'POST',
                            url     : self.$connectorUrl,
                            data    : {
                                action       : 'mgr/files',
                                method       : 'download',
                                HTTP_MODAUTH : self.$httpModAuth,
                                files        : files
                            },
                            success: function(data) {
                                if (data.results.status === 'success') {
                                    self.clearSelectedFiles();
                                    window.location.href = data.results.message;
                                    $dialog.dialog('close');
                                    return false;
                                }

                                $dialog.find('span[data-error]').html(data.results.message);
                            }
                        });
                    }
                }, {
                    text: e.target.dataset.downloadCancel,
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
         * Delete files.
         *
         * @param e
         */
        deleteFiles: function(e) {
            var self  = this,
                files = self.$selectedFiles;

            if (self.$currentFile !== 0) {
                files = self.$currentFile;
            }

            var $dialog = $('<div />').html(e.target.dataset.deleteMessage).dialog({
                draggable   : false,
                resizable   : false,
                modal       : true,
                title       : e.target.dataset.deleteTitle,
                buttons     : [{
                    text        : e.target.dataset.deleteConfirm,
                    class       : 'btn btn-danger',
                    click       : function () {
                        $.ajax({
                            type    : 'POST',
                            url     : self.$connectorUrl,
                            data    : {
                                action       : 'mgr/files',
                                method       : 'delete',
                                HTTP_MODAUTH : self.$httpModAuth,
                                files        : files
                            },
                            complete: function(data) {
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
         * Copy file to source.
         *
         * @param e
         */
        copyToSource: function(e) {
            var self = this;

            var $dialog = $('<div />').html(e.target.dataset.copyMessage).dialog({
                draggable: false,
                resizable: false,
                modal: true,
                title: e.target.dataset.copyTitle,
                buttons : [{
                    text: e.target.dataset.copyConfirm,
                    class: 'btn btn-primary',
                    click: function () {
                        $.ajax ({
                            type: 'POST',
                            url: self.$connectorUrl,
                            data: {
                                action       : 'mgr/files',
                                method       : 'copyToSource',
                                HTTP_MODAUTH : self.$httpModAuth,
                                fileId       : self.$currentFile
                            },
                            success: function(data) {
                                $dialog.html(data.results.message);
                                $dialog.next().find('.btn-primary').hide()
                            }
                        });
                    }
                }, {
                    text: e.target.dataset.copyCancel,
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
        filePopup: function(e, successMessage) {
            var self = this,
                template;

            if (typeof e !== 'undefined' && e !== null) {
                template = e.target.dataset.template;
            }

            if (typeof template === 'undefined') {
                template = 'preview';
            }

            // White loading screen
            $(self.$filePopupBody).html($('<div />').css('width', '100%').css('height', $(self.$filePopupBody).height()));

            // Get new template
            $.ajax({
                type: 'POST',
                url: self.$connectorUrl,
                data: {
                    action          : 'mgr/files',
                    HTTP_MODAUTH    : self.$httpModAuth,
                    method          : 'file',
                    id              : self.$currentFile,
                    template        : template
                },
                success: function(data) {
                    var $body   = $(self.$filePopupBody),
                        $footer = $(self.$filePopupFooter);

                    $body.html(data.results.body);
                    $footer.html(data.results.footer);

                    if (template === 'preview') {
                        $(self.$fileRelations, $body).on('click', function(e) {
                            e.preventDefault();

                            if (typeof e.target.dataset.fileId === 'undefined') {
                                return false;
                            }

                            self.$currentFile = e.target.dataset.fileId;
                            self.filePopup();
                        });

                        var $categories  = $(self.$fileCategories, $body).select2(self.$filterCategoriesOptions);
                        var $tags        = $(self.$fileTags, $body).select2(self.$filterTagsOptions);

                        // Add category to file
                        $categories.on('select2:select', function(e) {
                            $.ajax({
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
                            $.ajax({
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
                            $.ajax({
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
                            $.ajax({
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
                    }

                    if (template === 'edit') {
                        $(self.$fileEditSaveButton).on('click', function() {
                            var $form       = $(self.$editForm);
                            var data        = new FormData();
                            var formData    = $form.serializeArray();
                            var nestedData  = {};

                            $.each(formData, function (key, input) {
                                nestedData[input.name] = input.value;
                            });

                            data.append('data', JSON.stringify(nestedData));

                            if ($('input[name="license_file"]').length) {
                                var fileData = $('input[name="license_file"]')[0].files;
                                if (fileData.length > 0) {
                                    data.append('license_file', fileData[0]);
                                }
                            }

                            data.set('action', 'mgr/files');
                            data.set('method', 'save');
                            data.set('HTTP_MODAUTH', self.$httpModAuth);
                            data.set('fileId', self.$currentFile);

                            $.ajax({
                                type        : 'POST',
                                url         : self.$connectorUrl,
                                contentType : false,
                                processData : false,
                                data        : data,
                                success: function (data) {
                                    if (data.results.status === 'error') {
                                        $('[data-alert-messages]', $form).html(data.results.message);

                                        self.renderErrors($form, data.results.errors);
                                    } else {
                                        self.filePopup();
                                    }
                                }
                            });
                        });
                    }

                    if (template === 'crop') {
                        self.$filesCropper.init($(self.$fileCrop, $body), self);
                    }
                }
            });
        },

        /**
         * Select category when breadcrumb is clicked.
         *
         * @param e
         */
        selectCategory: function(e) {
            var self       = this,
                categoryId = parseInt(e.target.dataset.breadcrumbId),
                currentUrl = window.location.href,
                newUrl     = self.updateQueryStringParameter(currentUrl, 'category', categoryId);

            e.preventDefault();

            self.$currentCategory = categoryId;

            if (currentUrl !== newUrl) {
                history.pushState({}, '', newUrl);
            }

            $.each($(self.$categoryTree).treeview('getUnselected'), function(index, value) {
                if (categoryId === value.categoryId) {
                    $(self.$categoryTree).treeview('selectNode', [value.nodeId, {silent: true}]);
                }
            });

            self.clearSelectedFiles();
            self.getList();
        },

        alert: function(message, type) {
            if (typeof type === 'undefined') {
                type = 'danger';
            }

            return $('<div/>', {
                class: 'alert alert-' + type,
                text: message
            });
        },

        previewById: function (fileId)
        {
            this.$currentFile = fileId;
            this.filePopup();

            $(this.$filePopup).modal('show');
        },

        previewLink: function (e) {
            var self = this;

            self.$currentFile = e.target.dataset.fileId;
            self.filePopup();
            $(self.$filePopup).modal('show');
        },

        /**
         * Add meta fields inside file edit screen.
         */
        addMetaFields: function(event) {
            var $button = $(event.target);

            var index   = $button.parents('[data-edit-form]').find('input[type="text"]').filter(function() {
                return $(this).attr('name').match(/^meta\[ \d \]\[value\]/);
            }).length;

            var $template   = $('[data-meta-template]');
            var $clone      = $template.clone();

            $clone.removeClass('hide').removeAttr('data-meta-template');

            $clone
                .find('[name="key"]').attr('name', 'meta[ ' + index + ' ][key]');
            $clone
                .find('[name="value"]').attr('name', 'meta[ ' + index + ' ][value]');

            $clone.insertBefore($template);
        },

        /**
         * Remove meta fields from file edit screen.
         */
        removeMetaFields: function(event) {
             $(event.target).parents('.form-group').remove();
        },

        renderErrors: function ($group, errors)
        {
            if (errors) {
                $('.help-block', $group).remove();
                $('.form-group', $group).removeClass('has-error');

                for (var key of Object.keys(errors)) {
                    $element = $('[name^="' + key + '"]', $group);

                    if ($element.length) {
                        $element.parents('.form-group').addClass('has-error');

                        $('<p/>', {
                            class   : 'help-block',
                            text    : errors[key]
                        }).appendTo($element.parents('.form-group'));
                    }
                }
            }
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
        click : $.proxy(MediaManagerFiles, 'historyToggle')
    }, MediaManagerFiles.$fileHistoryButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'fileRevert')
    }, MediaManagerFiles.$fileRevertButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'dropzoneProcessQueue')
    }, MediaManagerFiles.$uploadSelectedFiles);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'advancedSearchOpen')
    }, MediaManagerFiles.$advancedSearch);

    $(document).on({
        change : $.proxy(MediaManagerFiles, 'changeSource')
    }, MediaManagerFiles.$selectSource);

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
        change : $.proxy(MediaManagerFiles, 'changeFilter')
    }, MediaManagerFiles.$filterDate);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'switchViewMode')
    }, MediaManagerFiles.$viewMode);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'selectFile')
    }, MediaManagerFiles.$fileContainer);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'selectCategory')
    }, MediaManagerFiles.$breadcrumb);

    // File popup actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'filePopup')
    }, MediaManagerFiles.$filePopupButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'filePopup')
    }, MediaManagerFiles.$fileActionButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'previewLink')
    }, MediaManagerFiles.$filePreviewLink);

    // File actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveFiles')
    }, MediaManagerFiles.$fileArchiveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveReplaceFile')
    }, MediaManagerFiles.$fileArchiveReplaceButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'shareFiles')
    }, MediaManagerFiles.$fileShareButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'deleteFiles')
    }, MediaManagerFiles.$fileDeleteButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'copyToSource')
    }, MediaManagerFiles.$fileCopyButton);

    // Bulk actions

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'moveFiles')
    }, MediaManagerFiles.$bulkMoveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'archiveFiles')
    }, MediaManagerFiles.$bulkArchiveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'unArchiveFiles')
    }, MediaManagerFiles.$bulkUnArchiveButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'deleteFiles')
    }, MediaManagerFiles.$bulkDeleteButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'shareFiles')
    }, MediaManagerFiles.$bulkShareButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'downloadFiles')
    }, MediaManagerFiles.$bulkDownloadButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'clearSelectedFiles')
    }, MediaManagerFiles.$bulkCancelButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'addMetaFields')
    }, MediaManagerFiles.$addMetaFieldsButton);

    $(document).on({
        click : $.proxy(MediaManagerFiles, 'removeMetaFields')
    }, MediaManagerFiles.$removeMetaFieldsButton);

}(jQuery);
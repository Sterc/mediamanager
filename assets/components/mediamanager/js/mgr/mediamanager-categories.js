+function ($) {

    var MediaManagerCategories = {

        $panelFit       : 'div[data-panel-fit]',
        $fitTo          : 'div[data-fit-to]',

        $createForm     : 'form[data-create-form]',
        $createFeedback : 'div[data-create-feedback]',
        $listing        : 'div[data-listing]',

        $edit           : 'a[data-edit-category]',
        $editConfirm    : '[data-edit-confirm]',

        $delete         : 'a[data-delete-category]',
        $deleteConfirm  : '[data-delete-confirm]',

        fit : function() {
            var self   = this,
                panels = $(self.$panelFit),
                height = $(window).height();

            $(self.$panelFit).css({'overflow-y': 'scroll', 'height': height - 180});
        },

        sortable : function() {
            var self   = this;

            $('.sortable').nestedSortable({
                                              forcePlaceholderSize: true,
                                              handle: 'div',
                                              items: 'li',
                                              placeholder: 'placeholder',
                                              toleranceElement: '> div',
                                              relocate: function() {
                                                  $.ajax ({
                                                              type: 'POST',
                                                              url: $(self.$createForm).attr('action'),
                                                              data: {
                                                                  action       : $('input[name="action"]', self.$createForm).val(),
                                                                  HTTP_MODAUTH : $('input[name="HTTP_MODAUTH"]', self.$createForm).val(),
                                                                  method       : 'sort',
                                                                  items        : $('.sortable').nestedSortable('serialize')
                                                              },
                                                              success: function(data) {
                                                                  //console.log(data);
                                                              }
                                                          });
                                              }
                                          });
        },

        create : function(e) {
            e.preventDefault();

            var self      = this,
                feedback  = $(self.$createFeedback),
                className = 'alert-success',
                values    = $(self.$createForm).serializeArray();

            feedback.html('');

            $.ajax ({
                        type: 'POST',
                        url: $(self.$createForm).attr('action'),
                        data: values,
                        success: function(data) {
                            if(data.results.error) {
                                className = 'alert-danger';
                            }
                            else {
                                $('select', self.$createForm).html(data.results.select);
                            }

                            $('<div/>', {
                                class: 'alert ' + className,
                                text: data.results.message
                            }).appendTo(feedback).delay(3000).fadeOut(300);

                            $('input[type="text"]', self.$createForm).val('');
                            $('input[type="checkbox"]', self.$createForm).attr('checked', false);
                            $(self.$listing).html(data.results.html);
                            self.sortable();
                        }
                    });

            return false;
        },

        edit : function(e) {
            var self = this,
                form  = $(self.$createForm).clone();

            form.find('button').remove();
            form.find('[name="parent"]').parents('.form-group').remove();
            form.find('[name="name"]').val(e.target.dataset.editName);
            form.find('[name="method"]').val('edit');
            form.removeAttr('data-create-form');

            form.append($('<input />', {name: 'category_id', type: 'hidden', value: e.target.dataset.editCategory}));

            var sources = e.target.dataset.editSources.split(',');
            $.each(sources, function(index, value) {
                form.find('input[value="' + value + '"]').prop('checked', true);
            });

            $('<div />').html(e.target.dataset.editMessage).append(form).dialog({
                                                                                    draggable: false,
                                                                                    resizable: false,
                                                                                    modal: true,
                                                                                    title: e.target.dataset.editTitle,
                                                                                    buttons : [{
                                                                                        text: e.target.dataset.editConfirm,
                                                                                        class: 'btn btn-success',
                                                                                        click: function () {
                                                                                            $.ajax ({
                                                                                                        type: 'POST',
                                                                                                        url: $(self.$createForm).attr('action'),
                                                                                                        data: $(form).serializeArray(),
                                                                                                        success: function(data) {
                                                                                                            $(self.$listing).html(data.results.html);
                                                                                                            $('select', self.$createForm).html(data.results.select);
                                                                                                        }
                                                                                                    });

                                                                                            $(this).dialog('close');
                                                                                        }
                                                                                    }, {
                                                                                        text: e.target.dataset.editCancel,
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

        delete : function(e) {
            var self        = this,
                count       = null,
                parentCount = null,
                options     = $('select', self.$createForm),
                select      = $('<select />', {class: 'form-control', name: 'delete-move-to'}).append(options.html());

            select.find('option[value="0"]').remove();
            $('option', select).each(function() {
                if($(this).val() === e.target.dataset.deleteCategory) {
                    parentCount = ($(this).text().match(/-/g) || []).length;

                    $(this).remove();
                }
                else if(parentCount !== null) {
                    count = ($(this).text().match(/-/g) || []).length;

                    if(count > parentCount) {
                        $(this).remove();
                    }
                    else {
                        return false;
                    }
                }
            });

            select.val(select.find('option:first').val());

            $('<div />').html(e.target.dataset.deleteMessage).append(select).dialog({
                                                                                        draggable: false,
                                                                                        resizable: false,
                                                                                        modal: true,
                                                                                        title: e.target.dataset.deleteTitle,
                                                                                        buttons : [{
                                                                                            text: e.target.dataset.deleteConfirm,
                                                                                            class: 'btn btn-danger',
                                                                                            click: function () {
                                                                                                $.ajax ({
                                                                                                            type: 'POST',
                                                                                                            url: $(self.$createForm).attr('action'),
                                                                                                            data: {
                                                                                                                action       : $('input[name="action"]', self.$createForm).val(),
                                                                                                                HTTP_MODAUTH : $('input[name="HTTP_MODAUTH"]', self.$createForm).val(),
                                                                                                                method       : 'delete',
                                                                                                                category_id  : e.target.dataset.deleteCategory,
                                                                                                                move_to      : select.val()
                                                                                                            },
                                                                                                            success: function(data) {
                                                                                                                $(self.$listing).html(data.results.html);
                                                                                                                $('select', self.$createForm).html(data.results.select);
                                                                                                            }
                                                                                                        });

                                                                                                $(this).dialog('close');
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
        }
    }

    $(document).ready(function() {
        MediaManagerCategories.fit();
        MediaManagerCategories.sortable();

        $(window).resize(function() {
            MediaManagerCategories.fit();
        });
    });

    $(document).on({
                       submit : $.proxy(MediaManagerCategories, 'create')
                   }, MediaManagerCategories.$createForm);

    $(document).on({
                       click : $.proxy(MediaManagerCategories, 'edit')
                   }, MediaManagerCategories.$edit);

    $(document).on({
                       click : $.proxy(MediaManagerCategories, 'delete')
                   }, MediaManagerCategories.$delete);

}(jQuery);
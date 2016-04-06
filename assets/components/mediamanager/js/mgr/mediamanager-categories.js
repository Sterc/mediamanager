+function ($) {

    var MediaManagerCategories = {

        $panelFit       : 'div[data-panel-fit]',
        $fitTo          : 'div[data-fit-to]',

        $createForm     : 'form[data-create-form]',
        $createFeedback : 'div[data-create-feedback]',
        $listing        : 'div[data-listing]',

        fit : function() {
            var self   = this,
                panels = $(self.$panelFit),
                height = $(window).height();

            $(self.$panelFit).css({'overflow-y': 'scroll', 'height': height - 150});
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
                            console.log(data);
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
                    $(self.$listing).html(data.results.html);
                    self.sortable();
                }
            });

            return false;
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

}(jQuery);
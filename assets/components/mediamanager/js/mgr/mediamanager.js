$(document).ready(function() {

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

    function getTree() {
        return tree;
    }

    $('.categories-tree').treeview({
        data: getTree(),
        levels: 1
    });


    $('.advanced-search').click(function() {
        $('.advanced-search-filters').slideToggle();
    });

    $('.upload-media').click(function() {
        $('.dropzone').slideToggle();
    });

    $('.upload-selected-files').click(function() {
        //dropzone.processQueue();
    });

});

Dropzone.options.dropzone = {
    maxFilesize: 10,
    maxThumbnailFilesize: 2,
    autoProcessQueue: true,
    dictDefaultMessage: '',
    previewsContainer: '.dropzone-previews',
    headers: {
        action: 'mgr/file/upload' // @TODO: need to be in the body
    },
    init: function() {
        this.on('addedfile', function(file) {
            $('.dropzone-actions').show(); // @TODO: Only activate button if categories are linked to media files
        });
    }
};
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
    maxFilesize: 100,
    maxThumbnailFilesize: 1,
    autoQueue: false,
    dictDefaultMessage: '',
    previewsContainer: '.dropzone-previews',
    params: {
        action: 'mgr/file/upload'
    },
    init: function() {
        this.on('addedfile', function(file) {
            $('.dropzone-actions').show(); // @TODO: Only activate button if categories are linked to media files
        });
    }
};
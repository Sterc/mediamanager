<div class="filters">

    <div class="form-inline">

        <div class="bulk-actions pull-left">
            <button type="button" class="btn btn-default" data-move-title="[[%mediamanager.files.bulk.move_title]]" data-move-confirm="[[%mediamanager.global.move]]" data-move-cancel="[[%mediamanager.global.cancel]]" data-bulk-move>[[%mediamanager.global.move]]</button>
            <button type="button" class="btn btn-default" data-archive-title="[[%mediamanager.files.bulk.archive_title]]" data-archive-message="[[%mediamanager.files.bulk.archive_message]]" data-archive-confirm="[[%mediamanager.global.archive]]" data-archive-cancel="[[%mediamanager.global.cancel]]" data-bulk-archive>[[%mediamanager.global.archive]]</button>
            <button type="button" class="btn btn-default" data-share-title="[[%mediamanager.files.bulk.share_title]]" data-share-message="[[%mediamanager.files.bulk.share_message]]" data-share-confirm="[[%mediamanager.global.share]]" data-share-cancel="[[%mediamanager.global.cancel]]" data-bulk-share>[[%mediamanager.global.share]]</button>
            <button type="button" class="btn btn-default" data-download-title="[[%mediamanager.files.bulk.download_title]]" data-download-message="[[%mediamanager.files.bulk.download_message]]" data-download-confirm="[[%mediamanager.global.download]]" data-download-cancel="[[%mediamanager.global.cancel]]" data-bulk-download>[[%mediamanager.global.download]]</button>
            <button type="button" class="btn btn-default" data-bulk-cancel>[[%mediamanager.global.cancel]]</button>

            <button type="button" class="btn btn-default hidden" data-archive-title="[[%mediamanager.files.bulk.unarchive_title]]" data-archive-message="[[%mediamanager.files.bulk.unarchive_message]]" data-archive-confirm="[[%mediamanager.global.unarchive]]" data-archive-cancel="[[%mediamanager.global.cancel]]" data-bulk-unarchive>[[%mediamanager.global.unarchive]]</button>
            <button type="button" class="btn btn-danger hidden" data-bulk-delete>[[%mediamanager.global.delete]]</button>
            
        </div>

        <div class="search-form pull-right">
            <select class="form-control" data-sorting>[[+sort_options]]</select>
            <input type="input" class="form-control" placeholder="[[+search]]" data-search>
            <button type="button" class="btn btn-default advanced-search" data-advanced-search>[[%mediamanager.files.advanced_search]]</button>
        </div>

        <div class="clearfix"></div>

        <div class="panel panel-default advanced-search-filters" data-advanced-search-filters>
            <div class="panel-body">

                <select class="form-control" data-filter-type>[[+filter_options.type]]</select>
                <select class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.categories]]" data-filter-categories></select>
                <select class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.tags]]" data-filter-tags></select>
                <select class="form-control" data-filter-user>[[+filter_options.users]]</select>
                <select class="form-control pull-right" data-filter-date>[[+filter_options.dates]]</select>

                <div class="clearfix" data-filter-date-custom>
                    <input class="form-control pull-right" type="text" placeholder="[[%mediamanager.files.filter.date_to]]" data-filter-date-to>
                    <input class="form-control pull-right" type="text" placeholder="[[%mediamanager.files.filter.date_from]]" data-filter-date-from>
                </div>

            </div>
        </div>

    </div>

</div>
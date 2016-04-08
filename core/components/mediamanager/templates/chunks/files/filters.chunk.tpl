<div class="filters">

    <div class="form-inline">

        <div class="bulk-actions pull-left">
            <button type="button" class="btn btn-default" data-move-title="[[%mediamanager.files.bulk.move_title]]" data-move-confirm="[[%mediamanager.global.move]]" data-move-cancel="[[%mediamanager.global.cancel]]" data-bulk-move>[[%mediamanager.global.move]]</button>
            <button type="button" class="btn btn-default" data-archive-title="[[%mediamanager.files.bulk.archive_title]]" data-archive-confirm="[[%mediamanager.global.archive]]" data-archive-cancel="[[%mediamanager.global.cancel]]" data-bulk-archive>[[%mediamanager.global.archive]]</button>
            <button type="button" class="btn btn-default" data-bulk-share>[[%mediamanager.global.share]]</button>
            <button type="button" class="btn btn-default" data-bulk-download>[[%mediamanager.global.download]]</button>
            <button type="button" class="btn btn-danger" data-bulk-delete>[[%mediamanager.global.delete]]</button>
            <button type="button" class="btn btn-default" data-bulk-cancel>[[%mediamanager.global.cancel]]</button>
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
                <select class="form-control" multiple="multiple" data-placeholder="Categories" data-filter-categories></select>
                <select class="form-control" multiple="multiple" data-placeholder="Tags" data-filter-tags></select>
                <select class="form-control" data-filter-user>[[+filter_options.users]]</select>

            </div>
        </div>

    </div>

</div>
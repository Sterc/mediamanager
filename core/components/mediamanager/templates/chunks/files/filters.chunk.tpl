<div class="filters">

    <div class="form-inline">

        <div class="search-form pull-right">
            <select class="form-control" data-sorting>[[+sort_options]]</select>
            <input type="input" class="form-control" placeholder="[[+search]]" data-search>
            <button type="button" class="btn btn-default advanced-search" data-advanced-search>[[+advanced_search]]</button>
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
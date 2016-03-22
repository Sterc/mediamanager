<div id="mediamanager-browser" class="mediamanager-browser">
    <div class="row">
        <div class="col-xs-12">
            <h1>
                <span>{$pagetitle}</span>
                <button type="button" class="btn btn-success upload-media" data-upload-media>{$upload_media}</button>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <form action="{$connector_url}" class="dropzone-form" id="mediaManagerDropzone" data-dropzone-form>
                <input type="hidden" name="action" value="mgr/files">
                <input type="hidden" name="HTTP_MODAUTH" value="{$token}">

                <h2>{$dropzone_title}</h2>
                <button type="button" class="btn btn-default btn-lg">{$dropzone_button}</button>
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
                <p class="max-upload-size">{$dropzone_maximum_upload_size}</p>
                <div class="dropzone-previews"></div>
                <div class="dropzone-actions">
                    <button type="button" class="btn btn-success pull-right upload-selected-files">{$upload_selected_files}</button>
                </div>
            </form>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-2">

            <select class="form-control select-context" data-select-context>
                <option>Media Context</option>
            </select>

            <div data-category-tree></div>

        </div>

        <div class="col-sm-9 col-md-10">

            <div class="media-header">

                <div class="form-inline">

                    <div class="search-form pull-right">
                        <select class="form-control">
                            <option>Sorting</option>
                        </select>
                        <input type="input" class="form-control" placeholder="{$search}">
                        <button type="button" class="btn btn-default advanced-search" data-advanced-search>{$advanced_search}</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="panel panel-default advanced-search-filters" data-advanced-search-filters>
                        <div class="panel-body">

                            <select class="form-control">
                                <option>Media type</option>
                            </select>

                            <select class="form-control">
                                <option>Categories</option>
                            </select>

                            <select class="form-control">
                                <option>Tags</option>
                            </select>

                            <select class="form-control">
                                <option>Uploaded by</option>
                            </select>

                            <select class="form-control">
                                <option>Date</option>
                            </select>

                        </div>
                    </div>

                </div>

            </div>

            <div class="media-container">

                <div class="panel panel-default">
                    <div class="panel-body"></div>
                </div>

            </div>

            <div class="media-footer">

            </div>

        </div>
    </div>

</div>
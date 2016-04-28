<div id="mediamanager-browser" class="mediamanager-browser">
    <div class="row">
        <div class="col-xs-12">
            <h1>
                <span>{$pagetitle}</span>
                {$upload_media_button}
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12" data-alert-messages></div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <div data-dropzone-feedback></div>

            <form action="{$connector_url}" class="dropzone-form clickable" id="mediaManagerDropzone" data-dropzone-form>
                <input type="hidden" name="HTTP_MODAUTH" value="{$token}">

                <h2 class="dz-message clickable">{$dropzone_title}</h2>
                <button type="button" class="btn btn-default btn-lg clickable">{$dropzone_button}</button>
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
                <p class="note clickable">{$dropzone_maximum_upload_size}</p>
                <div class="dropzone-previews"></div>
                <div class="dropzone-actions">
                    <button type="button" class="btn btn-success pull-right upload-selected-files" disabled>{$upload_selected_files}</button>
                </div>
            </form>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-lg-2">

            <select class="form-control select-source" data-select-source>
                {$source_list}
            </select>

            <div data-category-tree></div>

        </div>

        <div class="col-sm-9 col-lg-10">

            {$filters}

            <div class="panel panel-default">
                <div class="view-mode-grid panel-body" data-files></div>
            </div>

        </div>
    </div>

    {$popup}
    {$dropzoneFile}

</div>
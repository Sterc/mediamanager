<div class="hidden" data-dropzone-file-template>
    <div class="dz-preview dz-file-preview">
        <div class="row">
            <div class="col-sm-2 col-lg-1">
                <img data-dz-thumbnail />
            </div>
            <div class="col-sm-3 col-lg-4">
                <div class=""><span data-dz-name></span></div>
                <div class="" data-dz-size></div>
            </div>
            <div class="col-sm-4">
                <div class="categories">
                    <select name="c[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.categories]]" data-file-categories></select>
                </div>
                <div class="tags">
                    <select name="t[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.tags]]" data-file-tags></select>
                </div>
                <div class="source-tags">
                    <select name="ct[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.files.source_tags]]" data-file-source-tags></select>
                </div>
                <button type="button" class="btn btn-primary btn-copy">[[%mediamanager.files.copy_categories_and_tags]]</button>
            </div>
            <div class="col-sm-2">
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                </div>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-danger dz-remove pull-right" data-dz-remove="">[[%mediamanager.global.delete]]</button>
            </div>
        </div>
        <span data-dz-errormessage></span>
    </div>
</div>
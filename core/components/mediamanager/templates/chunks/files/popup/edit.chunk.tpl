<form action="/" method="get" data-edit-form>
    <div data-alert-messages></div>

    <div class="form-group">
        <label for="fileName">[[%mediamanager.files.file_name]]</label>
        <input type="name" class="form-control" id="fileName" name="filename" value="[[+file.name]]">
    </div>

    <h3>[[%mediamanager.files.custom_meta.title]]</h3>

    [[+meta]]

    <!-- The template for adding new field -->
    <div class="form-group hide" data-meta-template>
        <div class="row">
            <label class="col-md-5 control-label">[[%mediamanager.files.meta.key]]</label>
            <label class="col-md-7 control-label">[[%mediamanager.files.meta.value]]</label>
        </div>
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control" name="key" placeholder="[[%mediamanager.files.meta.key]]" />
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="value" placeholder="[[%mediamanager.files.meta.value]]" />
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-default" data-remove-meta><i class="fa fa-minus"></i></button>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-5 control-label">[[%mediamanager.files.meta.key]]</label>
            <label class="col-md-7 control-label">[[%mediamanager.files.meta.value]]</label>
        </div>
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control" name="meta[[[+metaCount]]][key]" placeholder="[[%mediamanager.files.meta.key]]" />
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="meta[[[+metaCount]]][value]" placeholder="[[%mediamanager.files.meta.value]]" />
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-default" data-add-meta><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
</form>
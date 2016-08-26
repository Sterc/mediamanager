<form action="/" method="get" data-edit-form>
    <div class="form-group">
        <label for="fileName">[[%mediamanager.files.file_name]]</label>
        <input type="name" class="form-control" id="fileName" name="filename" value="[[+file.name]]">
    </div>

    <h3>[[%mediamanager.files.add_meta_title]]</h3>

    [[+filemeta]]

    <div class="form-group">
        <label class="col-md-5 control-label">[[%mediamanager.files.meta.key]]</label>
        <label class="col-md-7 control-label">[[%mediamanager.files.meta.value]]</label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="[[+meta_startname]].metakey" placeholder="[[%mediamanager.files.meta.key]]" />
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" name="[[+meta_startname]].metavalue" placeholder="[[%mediamanager.files.meta.value]]" />
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
        </div>

        <div class="clearfix"></div>
    </div>

    <!-- The template for adding new field -->
    <div class="form-group hide" id="metaTemplate">

        <div class="col-md-5">
            <input type="text" class="form-control" name="metakey" placeholder="[[%mediamanager.files.meta.key]]" />
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" name="metavalue" placeholder="[[%mediamanager.files.meta.value]]" />
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
        </div>

        <div class="clearfix"></div>
    </div>
</form>
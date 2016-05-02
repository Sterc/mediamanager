<div class="form-group">
    <label class="col-md-5 control-label">[[%mediamanager.files.meta.key]]</label>
    <label class="col-md-7 control-label">[[%mediamanager.files.meta.value]]</label>

    <input type="hidden" name="[[+nameprefix]].metaid" value="[[+id]]" data-meta-id/>
    <div class="col-md-5">
        <input type="text" class="form-control" name="[[+nameprefix]].metakey" placeholder="[[%mediamanager.files.meta.key]]" value="[[+meta_key]]"/>
    </div>
    <div class="col-md-6">
        <input type="text" class="form-control" name="[[+nameprefix]].metavalue" placeholder="[[%mediamanager.files.meta.value]]" value="[[+meta_value]]"/>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
    </div>

    <div class="clearfix"></div>
</div>
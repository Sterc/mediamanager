<div class="form-group">
    <div class="row">
        <label class="col-md-5 control-label">[[%mediamanager.files.meta.key]] [[+required:eq=`1`:then=`*`:else=``]]</label>
        <label class="col-md-6 control-label">[[%mediamanager.files.meta.value]]</label>
    </div>
    <div class="row">
        <div class="col-md-5">
            <input type="[[+disabled:eq=`1`:then=`hidden`:else=`text`]]" class="form-control" name="meta[ [[+prefix]] ][key]" placeholder="[[%mediamanager.files.meta.key]]" value="[[+meta_key]]" />
            <input type="[[+disabled:eq=`1`:then=`text`:else=`hidden`]]" class="form-control" name="meta[ [[+prefix]] ][label]" value="[[+meta_label]]" readonly="readonly" />
        </div>
        <div class="col-md-6">
            <input type="text" class="form-control" name="meta[ [[+prefix]] ][value]" placeholder="[[%mediamanager.files.meta.value]]" value="[[+meta_value]]" />
        </div>
        <button type="button" class="btn btn-primary dz-ai pull-right"
                data-edit-call-ai="[[+meta_key]]" data-loading-text="[[%mediamanager.files.ai.loading]]">
            [[%mediamanager.files.ai.button]]
        </button>
        <button type="button" class="btn btn-success dz-ai dz-ai-apply pull-right hidden"
                data-apply-ai="[[+meta_key]]">[[%mediamanager.files.ai.apply.button]]</button>
        <button type="button" class="btn btn-danger dz-ai dz-ai-reset pull-right hidden"
                data-reset-ai="[[+meta_key]]">[[%mediamanager.files.ai.reset.button]]</button>
        <div class="col-md-1">
            [[+disabled:eq=`1`:then=`

            `:else=`
                <button type="button" class="btn btn-default" data-remove-meta><i class="fa fa-minus"></i></button>
            `]]
        </div>
    </div>
</div>

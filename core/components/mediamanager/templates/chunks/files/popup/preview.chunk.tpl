<div class="row">

    <div class="col-md-5">

        <div class="file-preview">
            [[+preview]]
        </div>

        <label class="spacing">[[%mediamanager.global.categories]]</label>
        <select name="categories[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.categories]]" data-file-categories [[+can_edit:is=`0`:then=` disabled`]]>
            [[+categories]]
        </select>

        <label class="spacing">[[%mediamanager.global.tags]]</label>
        <select name="tags[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.tags]]" data-file-tags [[+can_edit:is=`0`:then=` disabled`]]>
            [[+tags]]
        </select>

        [[-<label class="spacing">[[%mediamanager.files.source_tags]]</label>
        <select name="source_tags[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.files.source_tags]]" data-file-source-tags [[+can_edit:is=`0`:then=` disabled`]]>
            [[+source_tags]]
        </select>]]

    </div>
    <div class="col-md-7">

        <h3>[[%mediamanager.files.meta.title]]</h3>

        <table class="table table-striped">
            <colgroup>
                <col width="1">
                <col width="1">
            </colgroup>
            <tbody>
                <tr>
                    <td>[[%mediamanager.files.file_id]]</td>
                    <td>[[+file.id]]</td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_name]]</td>
                    <td>[[+file.name]]</td>
                </tr>
                [[+is_image:is=`1`:then=`
                <tr>
                    <td>[[%mediamanager.files.file_dimension]]</td>
                    <td>[[+file.file_dimensions]]</td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_size_available]]</td>
                    <td data-file-relations>[[+relations]]</td>
                </tr>
                `]]
                <tr>
                    <td>[[%mediamanager.files.file_size]]</td>
                    <td>[[+file.file_size]]</td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_uploaded_by]]</td>
                    <td>[[+file.uploaded_by_name]]</td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_upload_date]]</td>
                    <td>[[+file.upload_date]]</td>
                </tr>
                [[+file.is_archived:is=`0`:then=`
                <tr>
                    <td>[[%mediamanager.files.file_linked_to]]</td>
                    <td>[[+content]]</td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_link]]</td>
                    <td>
                        <a href="[[+file.path]]" target="_blank">
                            <input class="form-control" value="[[+file.path]]" readonly>
                        </a>
                    </td>
                </tr>
                `]]
            </tbody>
        </table>

        [[+filemeta:notempty=`
            <h3>[[%mediamanager.files.custom_meta.title]]</h3>

            <table class="table table-striped">
                <colgroup>
                    <col width="1">
                    <col width="1">
                </colgroup>
                <tbody>
                    [[+filemeta]]
                </tbody>
            </table>
        `:isempty=``]]

    </div>

    <div class="col-md-12">
        <div style="display:none;" data-history-table>
            <br/>
            <h3>File history</h3>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>[[%mediamanager.files.version]]</th>
                        <th>[[%mediamanager.files.file_name]]</th>
                        <th>[[%mediamanager.files.action]]</th>
                        <th>[[%mediamanager.files.type]]</th>
                        <th>[[%mediamanager.files.file_size]]</th>
                        [[+is_image:eq=`1`:then=`<th>[[%mediamanager.files.file_dimension]]</th>`]]
                        <th>[[%mediamanager.files.file_uploaded_by]]</th>
                        <th>[[%mediamanager.files.file_upload_date]]</th>
                        <th></th>
                    </tr>
               </thead>
                <tbody>
                    [[+history]]
                </tbody>
            </table>
        </div>
    </div>

</div>
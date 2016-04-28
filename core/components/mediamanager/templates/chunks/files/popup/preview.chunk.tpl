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

        <label class="spacing">[[%mediamanager.files.context_tags]]</label>
        <select name="context_tags[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.files.context_tags]]" data-file-context-tags [[+can_edit:is=`0`:then=` disabled`]]>
            [[+context_tags]]
        </select>

    </div>
    <div class="col-md-7">

        <h3>File information</h3>

        <table class="table table-striped">
            <colgroup>
                <col width="1">
                <col width="1">
            </colgroup>
            <tbody>
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
                    <td><input class="form-control" value="[[+file.full_link]]" readonly></td>
                </tr>
                `]]
            </tbody>
        </table>

    </div>

    <div class="col-md-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>[[%mediamanager.files.file_name]]</th>
                    <th>[[%mediamanager.files.file_dimension]]</th>
                    <th>[[%mediamanager.files.file_size_available]]</th>
                    <th>[[%mediamanager.files.file_size]]</th>
                </tr>
           </thead>
            <tbody>
                [[+history]]
            </tbody>
        </table>
    </div>

</div>
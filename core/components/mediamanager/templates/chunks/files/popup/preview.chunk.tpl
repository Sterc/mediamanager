<div class="row">

    <div class="col-md-5">

        [[+preview]]

        <select name="categories[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.categories]]" data-file-categories>
            [[+categories]]
        </select>

        <select name="tags[]" class="form-control" multiple="multiple" data-placeholder="[[%mediamanager.global.tags]]" data-file-tags>
            [[+tags]]
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
                    <td></td>
                </tr>
                `]]
                <tr>
                    <td>[[%mediamanager.files.file_size]]</td>
                    <td>[[+file.file_size]]</td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_uploaded_by]]</td>
                    <td><a href="?a=security/user/update&id=[[+file.uploaded_by]]">[[+file.uploaded_by_name]]</a></td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_upload_date]]</td>
                    <td>[[+file.upload_date]]</td>
                </tr>
                [[+file.is_archived:is=`0`:then=`
                <tr>
                    <td>[[%mediamanager.files.file_linked_to]]</td>
                    <td></td>
                </tr>
                <tr>
                    <td>[[%mediamanager.files.file_link]]</td>
                    <td><input class="form-control" value="[[+file.full_link]]" readonly></td>
                </tr>
                `]]
            </tbody>
        </table>

    </div>

</div>
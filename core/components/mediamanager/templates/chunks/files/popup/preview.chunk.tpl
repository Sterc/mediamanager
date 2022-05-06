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
            <tbody>
                <tr>
                    <td width="150px">[[%mediamanager.files.file_id]]</td>
                    <td>[[+file.id]]</td>
                </tr>
                <tr>
                    <td width="150px">[[%mediamanager.files.file_name]]</td>
                    <td>[[+file.name]]</td>
                </tr>

                [[+is_image:is=`1`:then=`
                    <tr>
                        <td width="150px">[[%mediamanager.files.file_dimension]]</td>
                        <td>[[+file.file_dimensions]]</td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.file_size_available]]</td>
                        <td data-file-relations>[[+relations]]</td>
                    </tr>
                `]]

                <tr>
                    <td width="150px">[[%mediamanager.files.file_size]]</td>
                    <td>[[+file.file_size]]</td>
                </tr>
                <tr>
                    <td width="150px">[[%mediamanager.files.file_uploaded_by]]</td>
                    <td>[[+file.uploaded_by_name]]</td>
                </tr>
                <tr>
                    <td width="150px">[[%mediamanager.files.file_upload_date]]</td>
                    <td>[[+file.upload_date]]</td>
                </tr>

                [[+file.is_archived:is=`0`:then=`
                    <tr>
                        <td width="150px">[[%mediamanager.files.file_linked_to]]</td>
                        <td>[[+content]]</td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.file_link]]</td>
                        <td>
                            <a href="[[+file.path]]" class="previewpopup-link" target="_blank">
                                [[+file.path]]
                            </a>
                        </td>
                    </tr>
                `]]
            </tbody>
        </table>

        [[+meta:notempty=`
            <h3>[[%mediamanager.files.custom_meta.title]]</h3>

            <table class="table table-striped">
                <tbody>
                    [[+meta]]
                </tbody>
            </table>
        `:isempty=``]]

        [[+licensing.id:notempty=`
            <h3>[[%mediamanager.files.licensing.title]]</h3>

            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td width="150px">[[%mediamanager.files.image_creation_date]]</td>
                        <td>
                            [[+licensing.image_createdon]]
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.image_source]]</td>
                        <td>
                            [[+licensing.image_source]]
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.image_valid_startdate]]</td>
                        <td>
                            [[+licensing.image_valid_startdate]]
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.image_valid_enddate]]</td>
                        <td>
                            [[+licensing.image_valid_enddate]]
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.license_depicted_consent]]</td>
                        <td>
                            [[+licensing.license_depicted_consent:eq=`1`:then=`
                                [[%mediamanager.global.yes]]
                            `:else=`
                                [[%mediamanager.global.no]]
                            `]]
                        </td>
                    </tr>
                    <tr>
                        <td width="150px">[[%mediamanager.files.license]]</td>
                        <td>
                            <a href="[[+licensing.license_path]]" class="previewpopup-link" target="_blank">[[+licensing.license_path]]</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        `:isempty=``]]
    </div>

    <div class="col-md-12">
        <div style="display:none;" data-history-table>
            <br/>
            <h3>[[%mediamanager.files.history]]</h3>

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
<tr class="file[[+selected:is=`1`:then=` file-selected`]]" data-id="[[+id]]" data-name="[[+name]]" data-preview="[[+path]]">
    <td>
        <input type="checkbox" name="file" value="[[+id]]"[[+selected:is=`1`:then=` checked`]] />
    </td>
    <td>
        <a href="[[+path]]" target="_blank">[[+name]]</a>
    </td>
    <td>
        [[+file_type]]
    </td>
    <td>
        [[+file_size]]
    </td>
    <td>
        [[+categories]]
    </td>
    <td>
        [[+uploaded_by]]
    </td>
    <td>
        [[+upload_date]]
    </td>
    <td>
        <div class="file-options pull-right">
            <button type="button" class="btn btn-success" data-file-use-button>[[%mediamanager.global.use]]</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="div[data-file-popup]" data-file-popup-button>
                <i class="fa fa-eye"></i>
            </button>
        </div>
    </td>
</tr>

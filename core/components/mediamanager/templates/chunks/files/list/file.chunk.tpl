<tr class="file[[+selected:is=`1`:then=` file-selected`]]" data-id="[[+id]]">
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
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="div[data-file-popup]" data-file-popup-button>[[%mediamanager.global.preview]]</button>
    </td>
</tr>
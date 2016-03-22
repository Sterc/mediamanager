<tr>
    <th scope="row">
        <span class="mediamanager-tag">[[+name]]</span>
        <form action="javascript:void(0)" class="form-inline hidden" method="post" data-edit-form>
            <input type="hidden" class="form-control" name="tag_id" value="[[+id]]">
            <div class="form-group">
                <input type="text" class="form-control" name="tag" value="[[+name]]">
            </div>
            <button type="submit" class="btn btn-success">[[%mediamanager.tags.save]]</button>
            <button type="submit" class="btn btn-default">[[%mediamanager.tags.cancel]]</button>
        </form>
    </th>
    <td>
        <a href="javascript:void(0)">
            [[%mediamanager.tags.edit]]
        </a> -
        <a href="javascript:void(0)" data-delete-tag="[[+id]]" data-delete-message="[[%mediamanager.tags.delete_confirm_message? &name=`[[+name]]`]]" data-delete-title="[[%mediamanager.tags.delete_confirm_title]]" data-delete-confirm="[[%mediamanager.tags.delete]]" data-delete-cancel="[[%mediamanager.tags.cancel]]">
            [[%mediamanager.tags.delete]]
        </a>
    </td>
</tr>
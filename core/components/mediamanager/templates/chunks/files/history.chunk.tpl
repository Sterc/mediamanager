<tr>
    <td>[[+version]]</td>
    <td><a href="[[+path]]" target="_blank">[[+file_name]]</a></td>
    <td>[[+action]]</td>
    <td>[[+type]]</td>
    <td>[[+file_size]]</td>
    [[+is_image:eq=`1`:then=`<td>[[+file_dimensions]]</td>`]]
    <td>[[+created_by]]</td>
    <td>[[+created]]</td>
    <td>
        [[+version:neq=`[[+active_version]]`:then=`
        <button type="button" class="btn btn-primary pull-right" data-version-id="[[+id]]" data-revert-button>[[%mediamanager.files.replace]]</button>
        `]]
    </td>

</tr>
[[+replaceHtml:notempty=`
    <tr>
        <td colspan="9">
            [[+replaceHtml]]
        </td>
    </tr>
`]]
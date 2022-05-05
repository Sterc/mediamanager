<tr style="background: [[+even:is=`1`:then=`#f3f3f3`:else=`#fff`]];" valign="top">
    <td style="padding: 5px;">
        <a href="[[+link]]">[[+item.name]]</a> ([[+item.id]])
    </td>
    [[+expires_in:notempty=`
        <td style="padding: 5px;">
            [[+expires_in]]
        </td>
    `]]
    <td style="padding: 5px; width: 150px; font-size: 12px; line-height: 15px;">
        [[+message]]
    </td>
</tr>

[[+resources:notempty=`
    <tr style="background: [[+even:is=`1`:then=`#f3f3f3`:else=`#fff`]];" valign="top">
        <td colspan="[[+expires_in:notempty=`3`:empty=`2`]]" style="padding:5px;">
            <strong>[[%mediamanager.license.email.resources]]</strong><br/>
            [[+resources]]
        </td>
    </tr>
`:isempty=``]]
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
            <table width="100%" style="border-collapse: collapse; border: 1px solid [[+even:is=`1`:then=`#fff`:else=`#f3f3f3`]];">
                <tr>
                    <th style="border-bottom: 1px solid [[+even:is=`1`:then=`#fff`:else=`#f3f3f3`]]; padding: 5px;">[[%mediamanager.license.email.resources]]</th>
                </tr>

                [[+resources]]
            </table>
        </td>
    </tr>
`:isempty=``]]
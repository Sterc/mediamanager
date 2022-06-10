<!doctype html>
<html>
<head>
    <title>[[++site_name]]</title>
    <meta charset="UTF-8">
</head>
<body style="padding: 20px; margin: 0; background-color: #f3f3f3;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td style="width: 100%; text-align: center;">

                <table cellpadding="20" cellspacing="0" border="0" width="600" style="font-family: Arial, sans-serif; font-size: 15px; line-height: 21px; color: #333; background-color: #fff; text-align: left; margin: 0 auto;">
                    <tr>
                        <td>
                            <h2>[[%mediamanager.license.email.image_validity.title]]</h2>
                            <p>[[%mediamanager.license.email.image_validity.msg]]</p>

                            [[+expiredItems:notempty=`
                                <h3 style="color: #FC2E20;margin-bottom: 5px;">[[%mediamanager.license.email.expired_images.title]]</h3>
                                <p style="margin: 0 0 5px 0;">[[%mediamanager.license.email.expired_images.msg]]</p>
                                <table width="100%" style="border-collapse: collapse;border: thin solid #f3f3f3;">
                                    <tbody>
                                        <tr style="background:#428bca;color:#fff;">
                                            <th style="padding: 5px;">[[%mediamanager.license.email.image]]</th>
                                            <th style="padding: 5px;">[[%mediamanager.license.email.message]]</th>
                                        </tr>
                                        [[+expiredItems]]
                                    </tbody>
                                </table>
                            `:isempty=``]]

                            [[+notifyItems:notempty=`
                                <h3 style="margin-bottom: 5px;">[[%mediamanager.license.email.about_to_expire_images.title]]</h3>
                                <p style="margin: 0 0 5px 0;">[[%mediamanager.license.email.about_to_expire_images.msg]]</p>
                                <table width="100%" style="border-collapse: collapse;border: thin solid #f3f3f3;">
                                    <tbody>
                                        <tr style="background:#428bca;color:#fff;border: thin solid #f3f3f3;">
                                            <th style="padding: 5px;">[[%mediamanager.license.email.image]]</th>
                                            <th style="padding: 5px;">[[%mediamanager.license.email.expires_in]]</th>
                                            <th style="padding: 5px;">[[%mediamanager.license.email.message]]</th>
                                        </tr>
                                        [[+notifyItems]]
                                    </tbody>
                                </table>
                            `:isempty=``]]

                            <p>
                                <small>[[%mediamanager.license.email.footer]]</small>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

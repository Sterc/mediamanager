# Media Manager for MODX [BETA]
![Media Manager version](https://img.shields.io/badge/version-0.3.3-brightgreen.svg)
![MODX Extra by Sterc](https://img.shields.io/badge/extra%20by-sterc-ff69b4.svg)

The Media Manager is a MODX Extra replacing the default Media Browser with an enterprise-grade media management solution, an initiative by [SEDA](https://seda.digital/) and [Sterc](https://www.sterc.com).

## Features
- Fully responsive
- Built on jQuery and Bootstrap for easier future development
- Fully database driven
- Virtual categories, instead of folders to allow a file to be in multiple categories
- Tag support
- Version control for your media
- Image editing features, including resizing and cropping
- Ability to publish/unpublish media with reference-checking
- Support for TinyMCE, Redactor 3.0, TV's, ContentBlocks
- Meta data for images out of the box: id, version, filename, version, author, dimensions (original + cropped image size), category, tags)
- Unlimited additional meta data (like TV's for images!)
- MODX ACL for removing/archiving images
- Attaching licenses to images including expiration dates

## Migrating from the default media browser
A migration tool is not yet available. You will not loose your old images, but they also will not show up in the new media browser.

## Installation
1. Install the package through package management by uploading the latest version from the _packages folder.
2. You have to enable the Media Manager per MediaSource. Go to the top-menu "Media" -> "Media Sources" -> "Create property": Name=mediamanagerSource, Type=Textfield, Value=1
3. Now enable it per user by editing a user: "Manage" -> "Users" -> Edit a user -> "Settings" -> "Create new" and set the key and/or name to "media_sources_id" and set the value to the ID of the Media Source you just enabled.

That's it, have fun!

## Redactor implementation
If you like to implement the mediamanager for Redactor 3.0, please take the following steps:
* Add the following path to the system setting **redactor.js**: `/assets/components/mediamanager/js/inputs/redactor_mediamanager.js`
* Go to the configuration set of redactor and add `mediamanager` under Miscellaneous --> Additional plugins
* Add `mediamanager` in the Toolbar Buttons list in the Toolbar tab

## Media Source settings

The following settings can be defined on media source level.

| Key                                | Description                                                                                                    | Example value                                                                                                                                                 |
|------------------------------------|----------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| mediamanagerSource                 | Determine if media source should use MediaManager.                                                             | 1                                                                                                                                                             |
| mediamanagerMeta                   | Set default meta fields and determine if fields should be marked as required or not.                           | [{"key":"author", "label":"Author", "required": true}, {"key":"photographer", "label":"Photographer", "required": true}, {"key": "editor", "label":"Editor"}] |
| mediamanagerLicenseEnabled         | Determine if license fields should be used.                                                                    | 1                                                                                                                                                             |
| mediamanagerLicenseSources         | Set available license sources with optional expiry date.                                                      | [{"key": "local", "label": "Local"},{"key": "getty_images", "label": "Getty Images", "expireson":  "24-03-2023"}]                                             |
| mediamanagerLicenseTestFrequencies | Frequencies (in days) to send out a notification email before image sources and/or images are about to expire. | ["14 days", "5 days", "1 days"]                                                                                                                               |
| mediamanagerLicenseTestRecipients  | Recipient emails that should receive image source/image license expiry notifications.                          | john@example.com,johndoe@example.com                                                                                                                          |


## Cron jobs
Run Cron jobs by calling the cron script and by specifying the jobs you'd like to run (comma delimited).

```
php /assets/components/mediamanager/cronjob/cron.php --jobs=TestImageSourceValidity,TestImageValidity
```

| Job                     | Description                                                                   |
|-------------------------|-------------------------------------------------------------------------------|
| TestImageValidity       | Job which checks image expiry and sends notification emails if needed.        |
| TestImageSourceValidity | Job which checks image source expiry and sends notification emails if needed. |


## BETA BETA
Please note that this manager is BETA. Please create your issues in Github or create Pull Requests. Any questions? Email us at modx@sterc.nl

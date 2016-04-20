[[+file.is_archived:is=`0`:then=`
    [[+is_image:is=`1`:then=`<button type="button" class="btn btn-default" data-template="crop" data-file-action-button>[[%mediamanager.global.crop]]</button>`]]
    <button type="button" class="btn btn-default" data-share-title="[[%mediamanager.files.share_title]]" data-share-message="[[%mediamanager.files.share_message]]" data-share-confirm="[[%mediamanager.global.share]]" data-share-cancel="[[%mediamanager.global.cancel]]" data-file-share-button>[[%mediamanager.global.share]]</button>
    <a href="[[+file.full_link]]" target="_blank" type="button" class="btn btn-default" data-file-download-button>[[%mediamanager.global.download]]</a>
    <button type="button" class="btn btn-danger pull-right" data-archive-title="[[%mediamanager.files.archive_title]]" data-archive-message="[[%mediamanager.files.archive_message]]" data-archive-confirm="[[%mediamanager.global.archive]]" data-archive-cancel="[[%mediamanager.global.cancel]]" data-file-archive-button>[[%mediamanager.global.archive]]</button>
`:else=`
    <button type="button" class="btn btn-danger pull-right" data-delete-title="[[%mediamanager.files.delete_title]]" data-delete-message="[[%mediamanager.files.delete_message]]" data-delete-confirm="[[%mediamanager.global.delete]]" data-delete-cancel="[[%mediamanager.global.cancel]]" data-file-delete-button>[[%mediamanager.global.delete]]</button>
`]]
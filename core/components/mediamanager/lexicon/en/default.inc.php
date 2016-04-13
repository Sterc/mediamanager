<?php
/**
 * Default English Lexicon Entries for Media Manager
 *
 * @package mediamanager
 * @subpackage lexicon
 */

$_lang['mediamanager'] = 'Media Manager';
$_lang['mediamanager.desc'] = 'View, upload and manage media';
$_lang['mediamanager.global.create'] = 'Create';
$_lang['mediamanager.global.add'] = 'Add';
$_lang['mediamanager.global.edit'] = 'Edit';
$_lang['mediamanager.global.delete'] = 'Delete';
$_lang['mediamanager.global.delete_confirm'] = 'Are you sure you want to delete this';
$_lang['mediamanager.global.search'] = 'Search';
$_lang['mediamanager.global.move'] = 'Move';
$_lang['mediamanager.global.crop'] = 'Crop';
$_lang['mediamanager.global.share'] = 'Share';
$_lang['mediamanager.global.archive'] = 'Archive';
$_lang['mediamanager.global.download'] = 'Download';
$_lang['mediamanager.global.cancel'] = 'Cancel';
$_lang['mediamanager.global.tags'] = 'Tags';
$_lang['mediamanager.global.categories'] = 'Categories';

/* Tags */
$_lang['mediamanager.tags'] = 'Media Tags';
$_lang['mediamanager.tags.desc'] = 'Manage media tags';
$_lang['mediamanager.tags.title'] = 'Create tag';
$_lang['mediamanager.tags.label'] = 'Tag';
$_lang['mediamanager.tags.save'] = 'Save';
$_lang['mediamanager.tags.placeholder'] = 'example: red';
$_lang['mediamanager.tags.button'] = 'Create';
$_lang['mediamanager.tags.error.empty'] = 'Cannot create tag because the name is empty.';
$_lang['mediamanager.tags.error.exists'] = 'Cannot create tag because it already exists.';
$_lang['mediamanager.tags.success'] = 'Tag `[[+name]]` is created.';
$_lang['mediamanager.tags.delete'] = 'Delete';
$_lang['mediamanager.tags.edit'] = 'Edit';
$_lang['mediamanager.tags.cancel'] = 'Cancel';
$_lang['mediamanager.tags.delete_success'] = 'Tag is deleted.';
$_lang['mediamanager.tags.delete_confirm_title'] = 'Delete';
$_lang['mediamanager.tags.delete_confirm_message'] = 'Are you sure you want to delete the tag `[[+name]]`.';

/* Categories */
$_lang['mediamanager.categories'] = 'Media Categories';
$_lang['mediamanager.categories.desc'] = 'Manage media categories';
$_lang['mediamanager.categories.root'] = 'root';
$_lang['mediamanager.categories.category'] = 'Category';
$_lang['mediamanager.categories.title'] = 'Create category';
$_lang['mediamanager.categories.label'] = 'Category';
$_lang['mediamanager.categories.parent_label'] = 'Parent category';
$_lang['mediamanager.categories.save'] = 'Save';
$_lang['mediamanager.categories.placeholder'] = 'example: Products';
$_lang['mediamanager.categories.button'] = 'Create';
$_lang['mediamanager.categories.error.empty'] = 'Cannot create category because the name is empty.';
$_lang['mediamanager.categories.error.exists'] = 'Cannot create category because it already exists.';
$_lang['mediamanager.categories.success'] = 'Category `[[+name]]` is created.';
$_lang['mediamanager.categories.delete'] = 'Delete';
$_lang['mediamanager.categories.edit'] = 'Edit';
$_lang['mediamanager.categories.cancel'] = 'Cancel';
$_lang['mediamanager.categories.delete_success'] = 'Category is deleted.';
$_lang['mediamanager.categories.delete_confirm_title'] = 'Delete';
$_lang['mediamanager.categories.delete_confirm_message'] = 'Are you sure you want to delete the category `[[+name]]`.';

/* Files */
$_lang['mediamanager.files.upload_media'] = 'Upload Media';
$_lang['mediamanager.files.upload_selected_files'] = 'Upload Selected Files';
$_lang['mediamanager.files.search'] = 'Search';
$_lang['mediamanager.files.advanced_search'] = 'Advanced Search';
$_lang['mediamanager.files.dropzone.maximum_upload_size'] = 'Maximum upload file size: [[+limit]].';
$_lang['mediamanager.files.dropzone.button'] = 'Or select files';
$_lang['mediamanager.files.dropzone.title'] = 'Drop files here to upload';
$_lang['mediamanager.files.sorting.name'] = 'Name';
$_lang['mediamanager.files.sorting.date'] = 'Date';
$_lang['mediamanager.files.filter.all_users'] = 'All users';
$_lang['mediamanager.files.filter.all_types'] = 'All types';
$_lang['mediamanager.files.filter.type_documents'] = 'Documents';
$_lang['mediamanager.files.filter.type_images'] = 'Images';
$_lang['mediamanager.files.filter.type_other'] = 'Other';
$_lang['mediamanager.files.error.no_files_found'] = 'No files found.';
$_lang['mediamanager.files.error.create_directory'] = 'Could not create upload directory.';
$_lang['mediamanager.files.error.create_zip'] = 'Could not create zip file.';
$_lang['mediamanager.files.error.file_exists'] = 'File `[[+file]]` already exists.';
$_lang['mediamanager.files.error.file_upload'] = 'File `[[+file]]` could not be uploaded.';
$_lang['mediamanager.files.error.file_save'] = 'File `[[+file]]` not added to database.';
$_lang['mediamanager.files.error.file_linked'] = 'File `[[+file]]` is used in resource.';
$_lang['mediamanager.files.error.file_archive'] = 'Could not archive file with id `[[+id]]`.';
$_lang['mediamanager.files.success.file_upload'] = 'File `[[+file]]` uploaded.';
$_lang['mediamanager.files.success.files_moved'] = 'Successfully moved files.';
$_lang['mediamanager.files.move_title'] = 'Move file';
$_lang['mediamanager.files.archive_title'] = 'Archive file';
$_lang['mediamanager.files.archive_message'] = 'Are you sure you want to archive this file?';
$_lang['mediamanager.files.share_title'] = 'Share file';
$_lang['mediamanager.files.share_message'] = 'Press the `Share` button to generate a link for this file.';
$_lang['mediamanager.files.share_download'] = 'Your share link: [[+link]] The share link will expire in [[+expiration]] days.';
$_lang['mediamanager.files.bulk.move_title'] = 'Move selected files';
$_lang['mediamanager.files.bulk.archive_title'] = 'Archive selected files';
$_lang['mediamanager.files.bulk.archive_message'] = 'Are you sure you want to archive these files?';
$_lang['mediamanager.files.bulk.share_title'] = 'Share selected files';
$_lang['mediamanager.files.bulk.share_message'] = 'Press the `Share` button to generate a link with the selected files.';
$_lang['mediamanager.files.file_dimension'] = 'Dimensions';
$_lang['mediamanager.files.file_size_available'] = 'Available sizes';
$_lang['mediamanager.files.file_upload_date'] = 'Upload date';
$_lang['mediamanager.files.file_size'] = 'Size';
$_lang['mediamanager.files.file_name'] = 'Name';
$_lang['mediamanager.files.file_uploaded_by'] = 'Uploaded by';
$_lang['mediamanager.files.file_linked_to'] = 'Linked to';
$_lang['mediamanager.files.file_link'] = 'Link';
$_lang['mediamanager.files.save_new_image'] = 'Save as new image';
$_lang['mediamanager.files.replace_image'] = 'Replace current image';

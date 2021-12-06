<?php
/**
 * Default English Lexicon Entries for Media Manager
 *
 * @package mediamanager
 * @subpackage lexicon
 */
$_lang['area_files'] = 'Files';

$_lang['mediamanager'] = 'Media Manager';
$_lang['mediamanager.desc'] = 'View, upload and manage media';
$_lang['mediamanager.global.create'] = 'Create';
$_lang['mediamanager.global.add'] = 'Add';
$_lang['mediamanager.global.edit'] = 'Edit';
$_lang['mediamanager.global.delete'] = 'Delete';
$_lang['mediamanager.global.delete_confirm'] = 'Are you sure you want to delete this';
$_lang['mediamanager.global.unarchive'] = 'Unarchive';
$_lang['mediamanager.global.search'] = 'Search';
$_lang['mediamanager.global.move'] = 'Move';
$_lang['mediamanager.global.crop'] = 'Crop';
$_lang['mediamanager.global.share'] = 'Share';
$_lang['mediamanager.global.archive'] = 'Archive';
$_lang['mediamanager.global.download'] = 'Download';
$_lang['mediamanager.global.history'] = 'History';
$_lang['mediamanager.global.cancel'] = 'Cancel';
$_lang['mediamanager.global.tags'] = 'Tags';
$_lang['mediamanager.global.categories'] = 'Categories';
$_lang['mediamanager.global.root'] = 'Root';
$_lang['mediamanager.global.copy'] = 'Copy';
$_lang['mediamanager.global.preview'] = 'Preview';
$_lang['mediamanager.global.save'] = 'Save';
$_lang['mediamanager.global.use'] = 'Use';

$_lang['mediamanager.global.error.mediasource'] = 'Your default mediasource (ID [[+mediasource_id]]) is not configured to use with the Media Manager. Please add a property \'mediamanagerSource\' with value \'1\' to this mediasource and all other mediasources you want to use with the Media Manager.';

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
$_lang['mediamanager.tags.notice'] = 'Select at least three tags before you want and can upload this file.';


/* Categories */
$_lang['mediamanager.categories'] = 'Media Categories';
$_lang['mediamanager.categories.desc'] = 'Manage media categories';
$_lang['mediamanager.categories.root'] = 'root';
$_lang['mediamanager.categories.category'] = 'Category';
$_lang['mediamanager.categories.title'] = 'Create category';
$_lang['mediamanager.categories.label'] = 'Category';
$_lang['mediamanager.categories.parent_label'] = 'Parent category';
$_lang['mediamanager.categories.source_label'] = 'Media source';
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
$_lang['mediamanager.categories.delete_confirm_message'] = 'Are you sure you want to delete the category `[[+name]]` and all the sub categories.<br /><br /> Move all files to category:';
$_lang['mediamanager.categories.edit_confirm_title'] = 'Edit';
$_lang['mediamanager.categories.edit_confirm_message'] = '';
$_lang['mediamanager.categories.minimum_categories_message'] = 'A file needs at least one category.';

/* Files */
$_lang['mediamanager.files.meta.title'] = 'File information';
$_lang['mediamanager.files.custom_meta.title'] = 'Custom file information';
$_lang['mediamanager.files.history'] = 'File history';
$_lang['mediamanager.files.upload_media'] = 'Upload Media';
$_lang['mediamanager.files.upload_selected_files'] = 'Upload Selected Files';
$_lang['mediamanager.files.add_meta_title'] = 'Add file information';
$_lang['mediamanager.files.meta.key'] = 'Name';
$_lang['mediamanager.files.meta.value'] = 'Value';
$_lang['mediamanager.files.search'] = 'Search';
$_lang['mediamanager.files.advanced_search'] = 'Advanced Search';
$_lang['mediamanager.files.dropzone.maximum_upload_size'] = 'Maximum upload file size: [[+limit]].<br>For images (png,jpg,gif) the maximum file size is [[+limit_images]].';
$_lang['mediamanager.files.dropzone.button'] = 'Or select files';
$_lang['mediamanager.files.dropzone.title'] = 'Drop files here to upload';
$_lang['mediamanager.files.sorting.name'] = 'Name A-Z';
$_lang['mediamanager.files.sorting.name.asc'] = 'Name Z-A';
$_lang['mediamanager.files.sorting.date'] = 'Date new - old';
$_lang['mediamanager.files.sorting.date.asc'] = 'Date old - new';
$_lang['mediamanager.files.filter.all_users'] = 'All users';
$_lang['mediamanager.files.filter.all_types'] = 'All types';
$_lang['mediamanager.files.filter.type_documents'] = 'Documents';
$_lang['mediamanager.files.filter.type_images'] = 'Images';
$_lang['mediamanager.files.filter.type_other'] = 'Other';
$_lang['mediamanager.files.filter.date_from'] = 'Date from';
$_lang['mediamanager.files.filter.date_to'] = 'Date to';
$_lang['mediamanager.files.filter.all_dates'] = 'All dates';
$_lang['mediamanager.files.filter.date_recent'] = 'Recent';
$_lang['mediamanager.files.filter.date_custom'] = 'Till from date';
$_lang['mediamanager.files.error.no_files_found'] = 'No files found.';
$_lang['mediamanager.files.error.meta_not_found'] = 'Could not find any metadata to delete from database with meta id [[+metaid]].';
$_lang['mediamanager.files.error.meta_not_removed'] = 'Could not remove data from database with meta id [[+metaid]].';
$_lang['mediamanager.files.error.create_directory'] = 'Could not create upload directory.';
$_lang['mediamanager.files.error.required_field'] = 'File `[[+file]]` could not be uploaded. One of the required fields is not filled, required fields are marked with an asterisk.';
$_lang['mediamanager.files.error.required_field_update'] = 'One of the required fields is not filled, required fields are marked with an asterisk.';
$_lang['mediamanager.files.error.create_zip'] = 'Could not create zip file.';
$_lang['mediamanager.files.error.version_not_found'] = 'Version [[+version]] could not be found.';
$_lang['mediamanaegr.files.error.revert_failed'] = 'Failed to revert [[+file]]. The revert could not be saved to database.';
$_lang['mediamanager.files.error.revertfile_failed'] = 'Failed to revert the file [[+file]] with the selected version of the file.';
$_lang['mediamanager.files.error.file_exists'] = 'File `[[+file]]` could not be uploaded. File `[[+file]]` already exists. [[+link]]';
$_lang['mediamanager.files.error.file_upload'] = 'File `[[+file]]` could not be uploaded.';
$_lang['mediamanager.files.error.file_save'] = 'File `[[+file]]` not added to database.';
$_lang['mediamanager.files.error.file_linked'] = 'File `[[+file]]` is used in resource.';
$_lang['mediamanager.files.error.file_archive'] = 'Could not archive file with id `[[+id]]`.';
$_lang['mediamanager.files.error.file_unarchive'] = 'Could not unarchive file with id `[[+id]]`.';
$_lang['mediamanager.files.error.file_not_found'] = 'File not found.';
$_lang['mediamanager.files.error.image_not_saved'] = 'Image not saved.';
$_lang['mediamanager.files.error.file_copy'] = 'Could not copy file `[[+file]]` to own source.';
$_lang['mediamanager.files.error.filetoobig'] = 'The file you selected is too big.';
$_lang['mediamanager.files.success.file_upload'] = 'File `[[+file]]` uploaded.';
$_lang['mediamanager.files.success.files_moved'] = 'Successfully moved files.';
$_lang['mediamanager.files.success.image_saved'] = 'Image saved.';
$_lang['mediamanager.files.success.file_copy'] = 'File `[[+file]]` copied to own source.';
$_lang['mediamanager.files.move_title'] = 'Move file';
$_lang['mediamanager.files.replace'] = 'Replace';
$_lang['mediamanager.files.archive_title'] = 'Archive file';
$_lang['mediamanager.files.archive_message'] = 'Are you sure you want to archive this file?';
$_lang['mediamanager.files.archive_and_replace'] = 'Archive & Replace';
$_lang['mediamanager.files.archive_and_replace_title'] = 'Archive & Replace file';
$_lang['mediamanager.files.archive_and_replace_message'] = 'Are you sure you want to archive and replace this file?';
$_lang['mediamanager.files.archive_and_replace_select_message'] = 'Please select a file you want to replace.';
$_lang['mediamanager.files.archive_and_replace_select_confirm'] = 'Are you sure you want to use this file?';
$_lang['mediamanager.files.share_title'] = 'Share file';
$_lang['mediamanager.files.share_message'] = 'Press the `Share` button to generate a link for this file.';
$_lang['mediamanager.files.share_download'] = 'Your share link: [[+link]] The share link will expire in [[+expiration]] days.';
$_lang['mediamanager.files.delete_title'] = 'Delete file';
$_lang['mediamanager.files.delete_message'] = 'Are you sure you want to delete this file?';
$_lang['mediamanager.files.copy_to_source'] = 'Copy file to own source';
$_lang['mediamanager.files.copy_to_source_message'] = 'Are you sure you want to copy this file to your own source?';
$_lang['mediamanager.files.bulk.move_title'] = 'Move selected files';
$_lang['mediamanager.files.bulk.archive_title'] = 'Archive selected files';
$_lang['mediamanager.files.bulk.archive_message'] = 'Are you sure you want to archive these files?';
$_lang['mediamanager.files.bulk.unarchive_title'] = 'Unarchive selected files';
$_lang['mediamanager.files.bulk.unarchive_message'] = 'Are you sure you want to unarchive these files?';
$_lang['mediamanager.files.bulk.share_title'] = 'Share selected files';
$_lang['mediamanager.files.bulk.share_message'] = 'Press the `Share` button to generate a link with the selected files.';
$_lang['mediamanager.files.bulk.download_title'] = 'Download selected files';
$_lang['mediamanager.files.bulk.download_message'] = 'Press the `Download` button to download the selected files.';
$_lang['mediamanager.files.bulk.delete_title'] = 'Delete selected files';
$_lang['mediamanager.files.bulk.delete_message'] = 'Press the `Delete` button to delete the selected files.';
$_lang['mediamanager.files.version'] = 'Version';
$_lang['mediamanager.files.action'] = 'Action';
$_lang['mediamanager.files.type'] = 'Type';
$_lang['mediamanager.files.file_id'] = 'ID';
$_lang['mediamanager.files.file_dimension'] = 'Dimensions';
$_lang['mediamanager.files.file_size_available'] = 'Available sizes';
$_lang['mediamanager.files.file_upload_date'] = 'Upload date';
$_lang['mediamanager.files.file_size'] = 'Size';
$_lang['mediamanager.files.file_name'] = 'Name';
$_lang['mediamanager.files.file_uploaded_by'] = 'Uploaded by';
$_lang['mediamanager.files.file_unknown_user'] = 'Unknown user';
$_lang['mediamanager.files.file_linked_to'] = 'Linked to';
$_lang['mediamanager.files.file_link'] = 'Link';
$_lang['mediamanager.files.save_new_image'] = 'Save as new image';
$_lang['mediamanager.files.save_image'] = 'Save image';
$_lang['mediamanager.files.copy_categories_and_tags'] = 'Use categories and tags above for all files';
$_lang['mediamanager.files.copy_values'] = 'Use the values above for all files';
$_lang['mediamanager.files.source_tags'] = 'Source tags';

/* Sources */
$_lang['mediamanager.sources.root'] = 'None';

/* Permissions */
$_lang['mediamanager.permissions.admin'] = 'Media Manager administrator permission';

/* Custom input/output filter */
$_lang['mm_input_image'] = 'Media Manager Image';
$_lang['mm_output_image'] = 'Media Manager Image';
$_lang['mm_input_file'] = 'Media Manager File';

/* Settings. */
$_lang['setting_mediamanager.max_file_size']             = 'Max file size';
$_lang['setting_mediamanager.max_file_size_desc']        = 'Maximum file size in MB.';
$_lang['setting_mediamanager.max_file_size_images']      = 'Max file size for images';
$_lang['setting_mediamanager.max_file_size_images_desc'] = 'Maximum file size for images in MB.';

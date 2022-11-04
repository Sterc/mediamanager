<?php
/**
 * Default German lexicon entries for Media Manager
 *
 * @package mediamanager
 * @subpackage lexicon
 */
$_lang['area_files']                            = 'Dateien';

$_lang['mediamanager']                          = 'Medien-Manager';
$_lang['mediamanager.desc']                     = 'Ansehen, Hochladen und Verwalten von Medien';
$_lang['mediamanager.global.create']            = 'Erstellen';
$_lang['mediamanager.global.add']               = 'Hinzufügen';
$_lang['mediamanager.global.edit']              = 'Bearbeiten';
$_lang['mediamanager.global.delete']            = 'Löschen';
$_lang['mediamanager.global.delete_confirm']    = 'Sind Sie sicher das Sie dies löschen möchten?';
$_lang['mediamanager.global.unarchive']         = 'Entpacken';
$_lang['mediamanager.global.search']            = 'Suchen';
$_lang['mediamanager.global.move']              = 'Verschieben';
$_lang['mediamanager.global.crop']              = 'Ausschneiden';
$_lang['mediamanager.global.share']             = 'Teilen';
$_lang['mediamanager.global.archive']           = 'Archivieren';
$_lang['mediamanager.global.download']          = 'Download';
$_lang['mediamanager.global.history']           = 'Historie';
$_lang['mediamanager.global.cancel']            = 'Abbrechen';
$_lang['mediamanager.global.tags']              = 'Tags';
$_lang['mediamanager.global.categories']        = 'Kategorien';
$_lang['mediamanager.global.root']              = 'Root';
$_lang['mediamanager.global.copy']              = 'Kopieren';
$_lang['mediamanager.global.preview']           = 'Vorschau';
$_lang['mediamanager.global.save']              = 'Speichern';
$_lang['mediamanager.global.use']               = 'Benutzen';
$_lang['mediamanager.global.yes']               = 'Ja';
$_lang['mediamanager.global.no']                = 'Nein';
$_lang['mediamanager.global.error.mediasource'] = 'Ihr default mediasource (ID [[+ mediasource_id]]) ist nicht mit dem Media Manager konfiguriert. Bitte fügen Sie eine Property \'mediamanagerSource \' mit dem Wert \'1 \' zu diesem mediasource und alle anderen mediasources Sie mit dem Media Manager verwenden möchten.';

/* Tags */
$_lang['mediamanager.tags']                                     = 'Medien-Tags';
$_lang['mediamanager.tags.desc']                                = 'Medien-Tags verwalten';
$_lang['mediamanager.tags.title']                               = 'Tag erstellen';
$_lang['mediamanager.tags.label']                               = 'Tag';
$_lang['mediamanager.tags.save']                                = 'Speichern';
$_lang['mediamanager.tags.placeholder']                         = 'Beispiel: red';
$_lang['mediamanager.tags.button']                              = 'Erstellen';
$_lang['mediamanager.tags.error.empty']                         = 'Tag konnte nicht erstellt werden, da der Name leer ist.';
$_lang['mediamanager.tags.error.exists']                        = 'Tag konnte nicht erstellt werden, da er bereits existiert.';
$_lang['mediamanager.tags.success']                             = 'Der Tag \'[[+name]]\' wurde erstellt.';
$_lang['mediamanager.tags.delete']                              = 'Löschen';
$_lang['mediamanager.tags.edit']                                = 'Bearbeiten';
$_lang['mediamanager.tags.cancel']                              = 'Abbrechen';
$_lang['mediamanager.tags.delete_success']                      = 'Der Tag wurde gelöscht.';
$_lang['mediamanager.tags.delete_confirm_title']                = 'Löschen';
$_lang['mediamanager.tags.delete_confirm_message']              = 'Sind Sie sicher, dass Sie den Tag \'[[+name]]\' löschen möchten?';
$_lang['mediamanager.tags.notice']                              = 'Bitte wählen Sie mindestens 3 Tags aus, bevor Sie die Datei hochladen.';

/* Categories */
$_lang['mediamanager.categories']                               = 'Medien-Kategorien';
$_lang['mediamanager.categories.desc']                          = 'Medien-Kategorien verwalten';
$_lang['mediamanager.categories.root']                          = 'root';
$_lang['mediamanager.categories.category']                      = 'Kategorie';
$_lang['mediamanager.categories.title']                         = 'Kategorie anlegen';
$_lang['mediamanager.categories.label']                         = 'Kategorie';
$_lang['mediamanager.categories.parent_label']                  = 'Eltern-Kategorie';
$_lang['mediamanager.categories.source_label']                  = 'Media source';
$_lang['mediamanager.categories.save']                          = 'Speichern';
$_lang['mediamanager.categories.placeholder']                   = 'Beispiel: Aktionen';
$_lang['mediamanager.categories.button']                        = 'Anlegen';
$_lang['mediamanager.categories.error.empty']                   = 'Die Kategorie konnte nicht angelegt werden, da der Name leer war.';
$_lang['mediamanager.categories.error.exists']                  = 'Die Kategorie konnte nicht angelegt werden, da sie bereits existiert.';
$_lang['mediamanager.categories.success']                       = 'Die Medien-Kategorie \'[[+name]]\' wurde angelegt.';
$_lang['mediamanager.categories.delete']                        = 'Löschen';
$_lang['mediamanager.categories.edit']                          = 'Bearbeiten';
$_lang['mediamanager.categories.cancel']                        = 'Abbrechen';
$_lang['mediamanager.categories.delete_success']                = 'Die Medien-Kategorie wurde gelöscht.';
$_lang['mediamanager.categories.delete_confirm_title']          = 'Löschen';
$_lang['mediamanager.categories.delete_confirm_message']        = 'Sie Sie sicher, dass Sie die Medien-Kategorie \'[[+name]]\' und alle Kind-Kategorien löschen möchten?<br /><br />Alle Dateien in folgende Kategorie verschieben:';
$_lang['mediamanager.categories.edit_confirm_title']            = 'Bearbeiten';
$_lang['mediamanager.categories.edit_confirm_message']          = '';
$_lang['mediamanager.categories.minimum_categories_message']    = 'Eine Datei braucht mindestens eine Medien-Kategorie.';

/* Files */
$_lang['mediamanager.files.meta.title']                         = 'Datei-Informationen';
$_lang['mediamanager.files.custom_meta.title']                  = 'Benutzerdefinierte Dateiinformationen';
$_lang['mediamanager.files.licensing.title']                    = 'Bildlizenzierung';
$_lang['mediamanager.files.image_validity.title']               = 'Bildgültigkeit';
$_lang['mediamanager.files.history']                            = 'Dateiversionen';
$_lang['mediamanager.files.upload_media']                       = 'Medien hochladen';
$_lang['mediamanager.files.upload_selected_files']              = 'Ausgewählte Dateien hochladen';
$_lang['mediamanager.files.add_meta_title']                     = 'Datei-Informationen hinzufügen';
$_lang['mediamanager.files.meta.key']                           = 'Name';
$_lang['mediamanager.files.meta.value']                         = 'Value';
$_lang['mediamanager.files.search']                             = 'Suche';
$_lang['mediamanager.files.advanced_search']                    = 'Erweiterte Suche';
$_lang['mediamanager.files.dropzone.maximum_upload_size']       = 'Maximale Dateigröße: [[+limit]].<br>Für Bilder (png, jpg, gif) liegt die maximale Dateigröße bei [[+limit_images]].';
$_lang['mediamanager.files.dropzone.button']                    = 'Oder Dateien auswählen';
$_lang['mediamanager.files.dropzone.title']                     = 'Dateien hier ablegen zum Hochladen';
$_lang['mediamanager.files.sorting.name']                       = 'Name A-Z';
$_lang['mediamanager.files.sorting.name.asc']                   = 'Name Z-A';
$_lang['mediamanager.files.sorting.date']                       = 'Datum neu - alt';
$_lang['mediamanager.files.sorting.date.asc']                   = 'Datum alt - neu';
$_lang['mediamanager.files.filter.all_users']                   = 'Alle Benutzer';
$_lang['mediamanager.files.filter.all_types']                   = 'Alle Typen';
$_lang['mediamanager.files.filter.type_documents']              = 'Dokumente';
$_lang['mediamanager.files.filter.type_images']                 = 'Bilder';
$_lang['mediamanager.files.filter.type_other']                  = 'Andere';
$_lang['mediamanager.files.filter.date_from']                   = 'Datum von';
$_lang['mediamanager.files.filter.date_to']                     = 'Datum bis';
$_lang['mediamanager.files.filter.all_dates']                   = 'Alle Daten';
$_lang['mediamanager.files.filter.date_recent']                 = 'Neuste';
$_lang['mediamanager.files.filter.date_custom']                 = 'Bis-Von-Datum';
$_lang['mediamanager.files.error.no_files_found']               = 'Keine Dateien gefunden.';
$_lang['mediamanager.files.error.meta_not_found']               = 'Es konnten keine Metadaten mit der Metadaten-ID [[+metaid]] zum Löschen gefunden werden.';
$_lang['mediamanager.files.error.meta_not_removed']             = 'Daten für die Metadaten-ID [[+metaid]] konnten nicht aus der Datenbank gelöscht werden.';
$_lang['mediamanager.files.error.create_directory']             = 'Das Upload-Verzeichnis konnte nicht erstellt werden.';
$_lang['mediamanager.files.error.required_field']               = 'Die Datei \'[[+file]]\' konnte nicht hochgeladen werden. Eines der Pflichtfelder ist nicht ausgefüllt, Pflichtfelder sind mit einem Stern gekennzeichnet.';
$_lang['mediamanager.files.error.required_field_update']        = 'Eines der Pflichtfelder ist nicht ausgefüllt, Pflichtfelder sind mit einem Stern gekennzeichnet.';
$_lang['mediamanager.files.error.create_zip']                   = 'Das ZIP-Archiv konnte nicht erstellt werden.';
$_lang['mediamanager.files.error.version_not_found']            = 'Version [[+version]] konnte nicht gefunden werden.';
$_lang['mediamanaegr.files.error.revert_failed']                = 'Die Datei [[+file]] konnte nicht zurückgesetzt werden. Die Änderung konnte nicht in der Datenbank vorgenommen werden.';
$_lang['mediamanager.files.error.revertfile_failed']            = 'Die Datei [[+file]] konnte nicht auf die gewählte Version zurückgesetzt werden.';
$_lang['mediamanager.files.error.file_exists']                  = 'Die Datei \'[[+file]]\' konnte nicht hochgeladen werden. Die Datei \'[[+file]]\' existiert bereits: [[+link]]';
$_lang['mediamanager.files.error.file_upload']                  = 'Die Datei \'[[+file]]\' konnte nicht hochgeladen werden.';
$_lang['mediamanager.files.error.file_save']                    = 'Die Datei \'[[+file]]\' konnte nicht in die Datenbank eingetragen werden.';
$_lang['mediamanager.files.error.file_linked']                  = 'Die Datei \'[[+file]]\' wird noch in einer Ressource genutzt.';
$_lang['mediamanager.files.error.file_archive']                 = 'Die Datei mit der ID \'[[+id]]\' konnte nicht archiviert werden.';
$_lang['mediamanager.files.error.file_unarchive']               = 'Die Datei mit der ID \'[[+id]]\' konnte nicht wiederhergestellt werden.';
$_lang['mediamanager.files.error.file_not_found']               = 'Datei nicht gefunden.';
$_lang['mediamanager.files.error.image_not_saved']              = 'Das Bild konnte nicht gespeichert werden.';
$_lang['mediamanager.files.error.file_copy']                    = 'Die Datei \'[[+file]]\' konnte nicht kopiert werden.';
$_lang['mediamanager.files.error.filetoobig']                   = 'Die ausgewählte Datei ist zu groß.';
$_lang['mediamanager.files.error.delete_dir']                   = 'Verzeichnis \'[[+dir]]\' konnte nicht gelöscht werden';
$_lang['mediamanager.files.success.file_upload']                = 'Die Datei \'[[+file]]\' wurde hochgeladen.';
$_lang['mediamanager.files.success.files_moved']                = 'Die Datei wurde erfolgreich verschoben.';
$_lang['mediamanager.files.success.image_saved']                = 'Bild gespeichert.';
$_lang['mediamanager.files.success.file_copy']                  = 'Datei \'[[+file]]\' wurde erfolgreich kopiert.';
$_lang['mediamanager.files.move_title']                         = 'Datei verschieben';
$_lang['mediamanager.files.replace']                            = 'Ersetzen';
$_lang['mediamanager.files.archive_title']                      = 'Datei archivieren';
$_lang['mediamanager.files.archive_message']                    = 'Sind Sie sicher, dass Sie diese Datei archivieren wollen?';
$_lang['mediamanager.files.archive_and_replace']                = 'Archivieren & Ersetzen';
$_lang['mediamanager.files.archive_and_replace_title']          = 'Archivieren & Datei ersetzen';
$_lang['mediamanager.files.archive_and_replace_message']        = 'Sind Sie sicher, dass Sie diese Datei archivieren und ersetzen möchten?';
$_lang['mediamanager.files.archive_and_replace_select_message'] = 'Bitte wählen Sie die Datei aus, die Sie ersetzen möchten.';
$_lang['mediamanager.files.archive_and_replace_select_confirm'] = 'Sind Sie sicher das Sie diese Datei wählen möchten?';
$_lang['mediamanager.files.archive_and_replace.success']        = 'Die ausgewählte Datei wurde archiviert und ersetzt.';
$_lang['mediamanager.files.share_title']                        = 'Datei teilen';
$_lang['mediamanager.files.share_message']                      = 'Klicken Sie auf den \'Teilen\' Button um einen öffenltichen Link für die Datei zu generieren.';
$_lang['mediamanager.files.share_download']                     = 'Ihr öffentlicher Link für diese Datei: [[+link]] Der Link läuft in [[+expiration]] Tagen ab.';
$_lang['mediamanager.files.delete_title']                       = 'Datei löschen';
$_lang['mediamanager.files.delete_message']                     = 'Sind Sie sicher das Sie die Datei löschen möchten?';
$_lang['mediamanager.files.copy_to_source']                     = 'Datei kopieren';
$_lang['mediamanager.files.copy_to_source_message']             = 'Sind Sie sicher, dass Sie die Datei kopieren möchten?';
$_lang['mediamanager.files.bulk.move_title']                    = 'Ausgewählte Dateien verschieben';
$_lang['mediamanager.files.bulk.archive_title']                 = 'Ausgewählte Dateien archivieren';
$_lang['mediamanager.files.bulk.archive_message']               = 'Sind Sie sicher, dass Sie die Dateien archivieren möchten?';
$_lang['mediamanager.files.bulk.unarchive_title']               = 'Ausgewählte Dateien wiederherstellen';
$_lang['mediamanager.files.bulk.unarchive_message']             = 'Sind Sie sicher, dass Sie die Dateien wiederherstellen möchten?';
$_lang['mediamanager.files.bulk.share_title']                   = 'Ausgewählte Dateien teilen';
$_lang['mediamanager.files.bulk.share_message']                 = 'Klicken Sie auf den \'Teilen\' Button um einen öffentlichen Link für die auswählten Dateien zu generieren';
$_lang['mediamanager.files.bulk.download_title']                = 'Ausgewählte Dateien herunterladen';
$_lang['mediamanager.files.bulk.download_message']              = 'Klicken Sie auf den \'Download\' Button um die ausgwählten Dateien herunterzuladen.';
$_lang['mediamanager.files.bulk.delete_title']                  = 'Ausgewählte Dateien löschen';
$_lang['mediamanager.files.bulk.delete_message']                = 'Drücken Sie die Schaltfläche \'Löschen\', um die ausgewählten Dateien zu löschen.';
$_lang['mediamanager.files.version']                            = 'Version';
$_lang['mediamanager.files.action']                             = 'Aktion';
$_lang['mediamanager.files.type']                               = 'Typ';
$_lang['mediamanager.files.file_id']                            = 'ID';
$_lang['mediamanager.files.file_dimension']                     = 'Auflösung';
$_lang['mediamanager.files.file_size_available']                = 'Verfügbare Größen';
$_lang['mediamanager.files.file_upload_date']                   = 'Upload-Datum';
$_lang['mediamanager.files.file_size']                          = 'Grö0e';
$_lang['mediamanager.files.file_name']                          = 'Name';
$_lang['mediamanager.files.file_uploaded_by']                   = 'Hochgeladen von';
$_lang['mediamanager.files.file_unknown_user']                  = 'Unbekannter Benutzer';
$_lang['mediamanager.files.file_linked_to']                     = 'Verknüpft mit';
$_lang['mediamanager.files.file_link']                          = 'Link';
$_lang['mediamanager.files.save_new_image']                     = 'Als neues Bild speichern';
$_lang['mediamanager.files.save_image']                         = 'Bild speichern';
$_lang['mediamanager.files.copy_categories_and_tags']           = 'Kategorien und Tags von oben für alle Dateien nutzen';
$_lang['mediamanager.files.copy_values']                        = 'Verwenden Sie die obigen Werte für alle Dateien';
$_lang['mediamanager.files.source_tags']                        = 'Source Tags';
$_lang['mediamanager.files.image_creation_date']                = 'Erstellungsdatum des Bildes';
$_lang['mediamanager.files.image_source']                       = 'Quelle';
$_lang['mediamanager.files.image_source_select']                = 'Wählen Sie eine Quelle aus';
$_lang['mediamanager.files.image_source_help']                  = 'Wählen Sie den Bildanbieter aus, bei dem das Bild gekauft wurde.';
$_lang['mediamanager.files.image_valid_startdate']              = 'Startdatum';
$_lang['mediamanager.files.image_valid_enddate']                = 'Endtermin';
$_lang['mediamanager.files.license']                            = 'Lizenz';
$_lang['mediamanager.files.license_exists']                     = 'Lizenz vorhanden?';
$_lang['mediamanager.files.license_depicted_consent']           = 'Das Einverständnis der abgebildeten Personen wurde eingeholt?';
$_lang['mediamanager.files.source_valid_until']                 = 'Gültig bis [[+date]]';
$_lang['mediamanager.files.license_file']                       = 'Hochladen der Lizenz- oder Zustimmungsdatei';
$_lang['mediamanager.files.license_file_help']                  = 'Erlaubte Dateierweiterungen sind [[+extensions]].';
$_lang['mediamanager.error.extension_not_allowed_for_field']    = 'Die Dateierweiterung ist für das Feld "[[+field]]" nicht zulässig. Erlaubte Dateierweiterungen sind [[+extensions]].';

/* Sources */
$_lang['mediamanager.sources.root']                                         = 'None';

/* Permissions */
$_lang['mediamanager.permissions.admin']                                    = 'Medien-Manager Administrationsrechte';

/* Custom input/output filter */
$_lang['mm_input_image']                                                    = 'Bild aus Medien-Manager';
$_lang['mm_output_image']                                                   = 'Bild aus Medien-Manager';
$_lang['mm_input_file']                                                     = 'Media Manager File';

/* Generic error messages. */
$_lang['mediamanager.error.required_field']                                 = '"[[+field]]" ist ein Pflichtfeld.';
$_lang['mediamanager.error.date_comparison']                                = '[[+date1]] can not be earlier then [[+date2]].';

/* Settings. */
$_lang['setting_mediamanager.max_file_size']                                = 'Maximale Dateigröße';
$_lang['setting_mediamanager.max_file_size_desc']                           = 'Maximale Dateigröße in MB.';
$_lang['setting_mediamanager.max_file_size_images']                         = 'Maximale Dateigröße für Bilder';
$_lang['setting_mediamanager.max_file_size_images_desc']                    = 'Maximale Dateigröße für Bilder in MB.';

/* License email. */
$_lang['mediamanager.license.email.image_source_validity.subject']          = 'Hinweis zur Gültigkeit der Bildquelle für Medienquelle "[[+mediasource]]" - [[+name]]';
$_lang['mediamanager.license.email.image_source_validity.title']            = 'Gültigkeit der Bildquelle';
$_lang['mediamanager.license.email.image_source_validity.msg']              = 'Der MediaManager hat Bildquellen gefunden, die abgelaufen sind oder kurz vor dem Ablauf stehen. Bitte überprüfen Sie die Informationen unten.';
$_lang['mediamanager.license.email.image_validity.subject']                 = 'Bildgültigkeitshinweis für Medienquelle "[[+mediasource]]" - [[+name]]';
$_lang['mediamanager.license.email.image_validity.title']                   = 'Bildgültigkeit';
$_lang['mediamanager.license.email.image_validity.msg']                     = 'Der MediaManager hat Bilder gefunden, die abgelaufen sind oder kurz vor dem Ablauf stehen. Bitte überprüfen Sie die Informationen unten.';
$_lang['mediamanager.license.email.expired_sources.title']                  = 'Abgelaufene Quellen';
$_lang['mediamanager.license.email.expired_sources.msg']                    = 'Die unten aufgeführten Bildquellen sind bereits abgelaufen.';
$_lang['mediamanager.license.email.expired_images.title']                   = 'Abgelaufene Bilder';
$_lang['mediamanager.license.email.expired_images.msg']                     = 'Die unten aufgeführten Bilder sind bereits abgelaufen.';
$_lang['mediamanager.license.email.about_to_expire_sources.title']          = 'Quellen laufen bald ab';
$_lang['mediamanager.license.email.about_to_expire_sources.msg']            = 'Die unten aufgeführten Bildquellen laufen bald ab.';
$_lang['mediamanager.license.email.about_to_expire_images.title']           = 'Bilder laufen bald ab';
$_lang['mediamanager.license.email.about_to_expire_images.msg']             = 'Die unten aufgeführten Bilder laufen bald ab.';
$_lang['mediamanager.license.email.resources']                              = 'Ressourcen';
$_lang['mediamanager.license.email.source']                                 = 'Quelle';
$_lang['mediamanager.license.email.expires_in']                             = 'Verfällt in';
$_lang['mediamanager.license.email.image']                                  = 'Bild';
$_lang['mediamanager.license.email.message']                                = 'Nachricht';
$_lang['mediamanager.license.email.image_source_expired']                   = 'Bildquelle abgelaufen.';
$_lang['mediamanager.license.email.image_about_to_expire_by_image_source']  = 'Das Bild läuft bald ab, basierend auf dem Ablaufdatum der Bildquelle.';
$_lang['mediamanager.license.email.footer']                                 = 'Dies ist eine automatische Nachricht, die vom <strong>MediaManager</strong> von <strong>[[++site_name]]</strong> gesendet wird.';

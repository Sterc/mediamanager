<?php

declare(strict_types=1);
/**
 * @var $object
 * @var array $options
 */
if ($object->xpdo instanceof modX) {
    $modx = $object->modx;
    $events = [
        'MediaManagerFileArchived',
        'MediaManagerFileDeleted',

        'MediaManagerFileVersionChanged',

        'MediaManagerFilesArchived',

        'MediaManagerVersionChanged',
    ];
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            foreach ($events as $eventName) {
                $event = $modx->getObject('modEvent', ['name' => $eventName]);
                if (!$event) {
                    $event = $modx->newObject('modEvent');
                    $event->set('name', $eventName);
                    $event->set('service', 6);
                    $event->set('groupname', 'MediaManager');
                    $event->save();
                }
            }

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            foreach ($events as $eventName) {
                $event = $modx->getObject('modEvent', ['name' => $eventName]);
                if ($event) {
                    $event->remove();
                }
            }

            break;
    }
}

return true;

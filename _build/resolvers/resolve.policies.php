<?php
/**
 * Auto-assign policies to appropriate User Groups
 *
 * @package mediamanager
 * @subpackage build
 *
 *
 * 
 * examples: 
 * https://github.com/modxcms/Discuss/blob/develop/_build/resolvers/resolve.policies.php
 * https://github.com/modxcms/Discuss/blob/develop/_build/build.transport.php
 *
 *
 * 
 * 
 */
if (!$object->xpdo) {
    return true;
}

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:

    	break;
    case xPDOTransport::ACTION_UPGRADE:
        break;
}
return true;
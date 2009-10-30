<?php

require_once('BadKitty.php');

include('../config/bootstrap.php');

require_once('../config/routes.php');

/**
 * Auto-load classes from the app/ directory. 
 * This saves a lot of effort.
 */
function __autoload($class_name) {
    require_once getenv('DOCUMENT_ROOT') . '/controllers/' . $class_name . '.php';
}

?>
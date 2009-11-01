<?php

require_once('BadKitty.php');
require_once('AppController.php');
require_once('AppModel.php');
require_once('Router.php');

include('../config/bootstrap.php');
include('../config/routes.php');

/**
 * Auto-load classes from the app/ directory. 
 * This saves a lot of effort.
 */
function __autoload($class_name) {
    require_once getenv('DOCUMENT_ROOT') . '/controllers/' . $class_name . '.php';
}

/**
 * Router shortcut function.
 * R('')->controller('test')->action('index')->on('GET');
 */

function R($pattern)
{
    return new Router($pattern);
}

?>
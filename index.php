<?php

/**
 * Yeah. You're definitely going to need this.
 */
require 'app/BadKitty.php';

/**
 * Controller names are class sensitive.
 */
R('')->controller('Demo')->action('index')->on();

/**
 * Auto-load classes from the app/ directory. 
 * This saves a lot of effort.
 */
function __autoload($class_name) {
    require_once 'controllers/' . $class_name . '.php';
}

/**
 * And ah... don't forget to actually run the application.
 */
run();

?>
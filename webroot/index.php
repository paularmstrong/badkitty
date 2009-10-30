<?php

/**
 * Yeah. You're definitely going to need this.
 * Probably don't edit anything in here.
 */
require getenv('DOCUMENT_ROOT') . '/app/bootstrap.php';

/**
 * And ah... don't forget to actually run the application.
 */
try
{
    BadKitty::getInstance()->dispatch();
}
catch (Exception $e)
{
    $klass = new AppController();
    $klass->e = $e;
    if (__DEBUG__ == TRUE)
    {
        $klass->title = 'Error';
        echo $klass->render('error.php');
    }
    else
    {
        $klass->title = '404 Not Found';
        echo $klass->render('error404.php');      
    }
}

?>
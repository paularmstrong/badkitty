<?php
/**
 * The HelloWorld of BadKitty
 */
class HelloWorld extends AppController
{
    
    public $models = array('HelloWorld');
    
    public function index()
    {
        $this->foo = 'Hello World';
        $this->title = 'Hello World';
        echo $this->render('index.php');
    }
    
}
?>

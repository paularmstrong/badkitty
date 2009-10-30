<?php

require 'app/BadKitty.php';

R('')->controller('test')->action('index')->on();

class Test extends AppController
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->foo = 'Hello';
        $this->title = 'Awesome';
        echo $this->render('index.php');
    }
}

run();

?>
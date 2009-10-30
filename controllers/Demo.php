<?php
class Demo extends AppController
{

    public function index()
    {
        $this->foo = 'Hello';
        $this->title = 'Awesome';
        echo $this->render('index.php');
    }
    
}
?>

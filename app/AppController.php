<?php

/*
 * Controller
 */
class AppController
{
    var $layout = TRUE;
    var $layout_template = 'layout.php';
    var $views = 'views';
    var $headers = NULL;
    var $title = '';
   
    function __construct()
    {
        foreach ($this->models as $key => $value) 
        {
            require_once(getenv('DOCUMENT_ROOT') . '/models/' . $value . '.php');
            $modelClass = $value . 'Model';
            $this->{$value} = new $modelClass();
        }
    }
   
    /* Render function return php rendered in a variable */
    public function render($file)
    {
        if ($this->layout == FALSE)
        {
            return $this->open_template(getenv('DOCUMENT_ROOT') . '/' . $this->views . '/' . $file);
        }
        else
        {
            $this->content = $this->open_template(getenv('DOCUMENT_ROOT') . '/' . $this->views . '/' . $file);
            return $this->open_template(getenv('DOCUMENT_ROOT') . '/' . $this->views . '/' . $this->layout_template);
        }
    }

    /* Open template to render and return php rendered in a variable using ob_start/ob_end_clean */
    private function open_template($name)
    {
        $vars = get_object_vars($this);
        ob_start();
        if (file_exists($name))
        {
            if (count($vars) > 0)
            {
                foreach ($vars as $key => $value) {
                    $$key = $value;
                }       
            }
            require($name);
        }
        else
        {
            throw new Exception('View [' . $name . '] Not Found');
        }
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }  
   
    /* Add information in header */
    public function header($text)
    {
        $this->headers[] = $text;
    }   
   
    /*
       Redirect page to annother place using header,
       $now indicates that dispacther will not wait all process
    */
    public function redirect($url, $now = FALSE)
    {
        if (!$now)
        {
            $this->header("Location: {$url}");
        }
        else
        {
            header("Location: {$url}");
        }
    }
   
}

?>

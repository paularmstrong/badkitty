<?php
/*
The MIT License

Copyright (c) 2007 Tiago Bastos

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/


/*
 * Controller
 */
class AppController 
{
    var $layout = TRUE;
    var $layout_tamplate = 'layout.php';
    var $views = 'views';
    var $headers = NULL;
    var $title = '';
    
    function __construct()
    {
    }
    
    /* Render function return php rendered in a variable */
    public function render($file)
    {
        if ($this->layout == FALSE)
        {
            return $this->open_template($file); 
        } 
        else 
        {
            $this->content = $this->open_template(getenv('DOCUMENT_ROOT') . '/' . $this->views . '/' . $file); 
            return $this->open_template(getenv('DOCUMENT_ROOT') . '/' . $this->views . '/' . $this->layout_tamplate); 
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
                foreach($vars as $key => $value){
                    $$key = $value;
                }        
            }
            require($name);
        }
        else
        {
            throw new Exception('View ['.$name.'] Not Found');
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
        if(!$now)
        {
            $this->header("Location: {$url}");
        }
        else
        {
            header("Location: {$url}");
        }
    } 
    
}

/*
 * Application core
 */
class BadKitty 
{
    var $routes = array();
    static private $instance = NULL ;
   
    function __construct()
    {
        if (isset($_GET['url']))
        {
            $this->url =trim( $_GET['url'], '/');
        }
        else
        {
            $this->url = '';
        }
    }
      
    /* Singleton */
    public function getInstance()
    {
        if(self::$instance == NULL)
        {
            self::$instance = new BadKitty();
        }
        
        return self::$instance;
    }   

    /* Add url to routes */
    public function add_url($rule, $klass, $klass_method, $http_method)
    {
        $this->routes[] = array(
            'route' => '/^' . str_replace('/','\/', $rule) . '$/', 
            'controller' => $klass, 
            'action' => $klass_method, 
            'method' => $http_method
        );
    }
    
    /* Process requests and dispatch */
    public function dispatch()
    {
        foreach($this->routes as $rule => $conf) {
            if (
                (
                    preg_match($conf['route'], $this->url, $matches) 
                    && empty($conf['method'])
                )
                ||
                (
                    preg_match($conf['route'], $this->url, $matches) 
                    &&
                    (
                        !empty($conf['method'])
                        && (getenv('REQUEST_METHOD') == $conf['method'])
                    )
                )
            )
            {
                // only declared variables in url regex
                $matches = $this->parse_urls_args($matches); 
                $klass = new $conf['controller']();
                
                // set the default title to the action name
                $klass->title = ucwords($conf['action']);
                
                ob_start();
                call_user_func_array(array($klass , $conf['action']), $matches);  
                $out = ob_get_contents();
                ob_end_clean();  
                
                if (count($klass->headers) > 0)
                {
                    foreach($klass->headers as $header){
                        header($header);
                    }
                } 
                
                print $out;      
                
                // Argh! Its not pretty, but usefull...
                exit(); 
            }    
        }
        
        call_user_func_array(array($this, 'error'), getenv('REQUEST_METHOD'));
    }

    public function error($method = NULL)
    {
        header('HTTP/1.0 404 Not Found');

        if (__DEBUG__)
        {
            throw new Exception($this->url . ' Not Found');
        }
        else
        {
            $klass = new AppController();
            $klass->url = '/' . $this->url;
            $klass->url = '404 Not Found';
            echo $klass->render('error404.php');
        }
    }
    
    /* Parse url arguments */
    private function parse_urls_args($matches)
    {
        $first = array_shift($matches);
        $new_matches = array();
        
        foreach($matches as $k=>$match)
        {
            if (is_string($k))
            {
                $new_matches[$k]=$match;
            }
        }
        
        return $new_matches;
    }
}
 
/*
 *  New routes,  just sugar!
 *  R('','Test','index','GET'); turns: 
 *  R('')->controller('test')->action('index')->on('GET');
 * Thanks to:  Rafael S. Souza <rafael.ssouza [__at__] gmail.com>
 */
 
function R($pattern)
{
    return new Route($pattern);
}

class Route 
{
    var $pattern;
    var $controller;    
    var $action;
    var $http_method = 'GET';
    
    function __construct($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }
    
    function controller($controller)
    {
        $this->controller = $controller;
        return $this;
    }
    
    function action($action)
    {
        $this->action = $action;
        return $this;
    }
    
    function on($http_method = NULL)
    {
        $this->http_method = $http_method;
        $this->bind();
        return $this;
    }
    
    function bind()
    {
        $router = BadKitty::getInstance()->add_url($this->pattern, $this->controller, $this->action, $this->http_method);
    }
}

/*
 * Run application
 */
function Run()
{
    try 
    {
        BadKitty::getInstance()->dispatch();
    } 
    catch (Exception $e) 
    {
        $app = new AppController();
        $app->e = $e;
        if (__DEBUG__ == TRUE) 
        {
            $app->title = 'Error';
            echo $app->render('error.php');
        } 
        else 
        {
            $app->title = '404 Not Found';
            echo $app->render('error404.php');       
        }
    }
    
}
?>

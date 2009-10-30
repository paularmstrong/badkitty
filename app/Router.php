<?php

class Router
{
    var $pattern;
    var $controller;   
    var $action;
    var $http_method;
   
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

?>
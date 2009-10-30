<?php

/**
 * Controller names are class sensitive.
 * 
 * Example:
 * R('tags/(?P<tag>[-\w]+)')->controller('Tags')->action('view')->on('GET');
 * 
 */
R('')->controller('HelloWorld')->action('index')->on();

?>
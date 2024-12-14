<?php

require '../core/Router.php';

$url = $_SERVER['QUERY_STRING'];
echo "url = " .$url;

$router = new Router();

$router->add('/public',array(
    'controller' => 'Home',
    'action' => 'index'
));



$urlParams = explode('/',$url);

$urlArray = array(
    'HTTP' => $_SERVER['REQUEST_MATHOD'],
    'path' => $url,
    'controller' => '',
    'action' => '',
    'params' => ''

);
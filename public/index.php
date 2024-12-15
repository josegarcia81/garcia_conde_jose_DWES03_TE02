<?php

require '../core/Router.php';
require '../app/controllers/Post.php';

$url = $_SERVER['QUERY_STRING'];
echo "url = " .$url;

$router = new Router();

$router->add('/public/post/get',array(
    'controller' => 'Post',
    'action' => 'getAll'
));

$router->add('/public/post/get/{id}',array(
    'controller' => 'Post',
    'action' => 'getById'
));

$router->add('/public/post/create',array(
    'controller' => 'Post',
    'action' => 'createIncidencia'
));

$router->add('/public/post/update/{id}',array(
    'controller' => 'Post',
    'action' => 'updateIncidencia'
));

$router->add('/public/post/delete/{id}',array(
    'controller' => 'Post',
    'action' => 'delIncidencia'
));


$urlParams = explode('/',$url);

$urlArray = array(
    'HTTP' => $_SERVER['REQUEST_MATHOD'],
    'path' => $url,
    'controller' => '',
    'action' => '',
    'params' => ''

);

// Validar la url que viene
if (!empty($urlParams[2])){
    $urlArray['controller'] = ucwords($urlParams[2]);
    
    if(!empty($urlParams[3])){
        $urlArray['action'] = $urlParams[3];
        if(!empty($urlParams[4])){
            $urlArray['params'] = $urlParams[4];
        }
    } else {
        $urlArray['action'] = 'index';
    }
} else {
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}

if($router->matchRoute($urlArray)){
    $method = $_SERVER['REQUEST_METHOD'];

    $params = [];

    if($method == 'GET'){

        $params[] = intval($urlParams['params']) ?? null;

    } elseif($method == 'POST'){
        // lee el body de la peticion
        $json = file_get_contents('php://input');
        $params = json_decode($json,true);

    } elseif($method == 'PUT'){

        $id = intval($urlParams['params']) ?? null;
        // lee el body de la peticion
        $json = file_get_contents('php://input');
        $params[] = $id;
        $params[] = json_decode($json,true);
        
    } elseif($method == 'DELETE'){
        
        $params[] = intval($urlParams['params']) ?? null;

    }

    // Creear controlador y metodo dinamicamente
    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];
    $controller = new $controller();

    if(method_exists($controller, $action)){

        $resp = call_user_func_array([$controller, $action],$params);

    } else {
        echo 'El método no existe';
    }
}
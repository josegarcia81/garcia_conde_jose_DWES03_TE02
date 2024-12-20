<?php

// CARGA DE ARCHIVOS NECESARIOS //
require '../core/Router.php';
require '../app/controllers/Post.php';
//require '../app/models/Incidencia.php';

$url = $_SERVER['QUERY_STRING'];

$router = new Router();
// CREAR LAS RUTAS EN LA INSTANCIA ROUTER //
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
    'action' => 'deleteIncidencia'
));

// dividir lo que viene por la url y crear un array con los datos 
$urlParams = explode('/',$url);
// inicializar array que va a contener los datos para luego seleccionar el controller y metodo
$urlArray = array(
    'HTTP' => $_SERVER['REQUEST_METHOD'],
    'path' => $url,
    'controller' => '',
    'action' => '',
    'params' => ''

);

// Validar la url que nos viene //
if (!empty($urlParams[2])){
    $urlArray['controller'] = ucwords($urlParams[2]);
    
    if(!empty($urlParams[3])){
        $urlArray['action'] = $urlParams[3];
        if(!empty($urlParams[4])){
            $urlArray['params'] = $urlParams[4];
        }
    } else {
        $urlArray['action'] = 'index';
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "code" => 404,
            "time" => date('c'), // Fecha y hora en formato ISO-8601
            "message" => "URL no valida",
            "data" => []
        ]);
    }
} else {
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "code" => 404,
        "time" => date('c'), // Fecha y hora en formato ISO-8601
        "message" => "URL no valida",
        "data" => []
    ]);
}

// En funcion del RouteMatch filtramos el tipo de peticion y ejecutamos 
// un metodo diferente de nuestro contolador Post.php
if($router->matchRoutes($urlArray)){
    
    $method = $_SERVER['REQUEST_METHOD'];
    //$method = 'GET';
    $params = [];

    if($method == 'GET'){

        $params[] = intval($urlArray['params']) ?? '';
        //$params[] = $dbData;

    } elseif($method == 'POST'){
        // lee el body de la peticion
        $json = file_get_contents("php://input");
        $params[] = json_decode($json,true);
        //$params[] = $dbData;

    } elseif($method == 'PUT'){

        $id = intval($urlArray['params']) ?? null;
        // lee el body de la peticion
        $json = file_get_contents("php://input");
        $params[] = $id;
        $params[] = json_decode($json,true);
        //$params[] = $dbData;
        
    } elseif($method == 'DELETE'){
        
        $params[] = intval($urlArray['params']) ?? null;
        //$params[] = $dbData;

    }

    // Creear controlador y metodo dinamicamente
    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];
    $controller = new $controller();

    if(method_exists($controller, $action)){

        call_user_func_array([$controller, $action],$params);

    } else {
        echo 'El método no existe';
    }
}

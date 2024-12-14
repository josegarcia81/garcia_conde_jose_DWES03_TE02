<?php   

class Router{

    protected $routes = array();

    public function add($route, $params){
        $this->routes[$route] = $params;
    }

    public function getRoutes(){
        return $this->routes;
    }

}
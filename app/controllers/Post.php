<?php

class Post{
    function _construct(){

    }

    function getAll(){
        echo 'Metodo getAll que recupera todas las incidencias';
    }

    function getById($id){
        echo 'Metodo getById que recupera una incidencia';
    }

    function createIncidencia($data){
        echo 'Metodo createIncidencia que crea una incidencia';
        echo 'datos: ' .json_encode($data);
    }

    function updateIncidencia($id,$data){
        echo 'Metodo updateIncidencia que actualiza una incidencia';
        echo 'datos: ' .json_encode($data);
    }

    function delIncidencia($id){
        echo 'Metodo delIncidencia que borra una incidencia';
    }

}
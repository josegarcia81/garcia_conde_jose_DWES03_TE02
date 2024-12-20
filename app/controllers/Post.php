<?php

require __DIR__ . '/../models/Incidencia.php';

class Post{

    public array $dbData = [];

    function _construct(){
        
    }

//// FUNCION QUE DEVUELVE TODAS LAS INCIDENCIAS ////
    function getAll(){
        //echo 'Metodo getAll que recupera todas las incidencias';

        // Revisar si el array de la base de datos esta cargado y sino cargarlo //
        if(empty($this->dbData)){
            $dbData = $this->cargarDb();
        } else {
            $dbData = $this->dbData;
        }
        // Comprobar que ha cargado la base de datos correctamente
        if(!$dbData === false){
            // Devolver la respuesta con los datos de la base de datos
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "code" => 200,
                "time" => date('c'), // Fecha y hora en formato ISO-8601
                "message" => "Base de datos con todas las incidencias",
                "data" => $dbData
            ]);
        }
    }

//// FUNCION QUE DEVUELVE UNA INCIDENCIA BUSCADA POR ID ////
    function getById($id){
        
        // Revisar si el array de la base de datos esta cargado y sino cargarlo //
        if(empty($this->dbData)){
            $dbData = $this->cargarDb();
        } else {
            $dbData = $this->dbData;
        }
        
        $encontrada = false;
        // Comprobar que ha cargado la base de datos correctamente
        if(!$dbData === false){
            foreach($dbData as $incidencia){
                if($incidencia->id == $id){

                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        "status" => "success",
                        "code" => 200,
                        "time" => date('c'), // Fecha y hora en formato ISO-8601
                        "message" => "Incidencia Encontrada",
                        "data" => $incidencia
                    ]);
                    $encontrada = true;
                } 
            }

            // Envio de error 406 ni no se encuentra //
            if(!$encontrada){
                header('Content-Type: application/json');
                http_response_code(406);
                echo json_encode([
                    "status" => "Not Found",
                    "code" => 406,
                    "time" => date('c'), // Fecha y hora en formato ISO-8601
                    "message" => "Incidencia no encontrada en la Base de Datos",
                    "data" => $id
                ]);
                return;
            }

        }
        

    }

//// FUNCION QUE CREA UNA INCIDENCIA ////
    function createIncidencia($data){
        
        
        // Revisar si el array de la base de datos esta cargado y sino cargarlo //
        if(empty($this->dbData)){
            $dbData = $this->cargarDb();
        } else {
            $dbData = $this->dbData;
        }
        // Comprobar que ha cargado la base de datos correctamente
        if(!$dbData === false){
            // recogida de datos
            $id = $data["id"];
            $trabajador = $data["trabajador"];
            $hora= $data["hora"];
            $instalacion= $data["instalacion"];
            $descripcion= $data["descripcion"];
            
            //crear nueva incidencia de tipo Incidencia
            $data = new Incidencia($id,$trabajador,$hora,$instalacion,$descripcion);

            // comprobar que se incluye en el array y a la vez aniadirlo
            if(!array_push($dbData, $data)){
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    "status" => "error",
                    "code" => 500,
                    "time" => date('c'), // Fecha y hora en formato ISO-8601
                    "message" => "Error interno",
                    "data" => ''
                ]);
                return;
            };
            
            // Llamada a funcion que guarda los cambios
            $this->guardarDb($dbData);

            // datos de respuesta
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "code" => 201,
                "time" => date('c'), // Fecha y hora en formato ISO-8601
                "message" => "Datos con la incidencia CREADA",
                "data" => $data
            ]);
        }    
    }

//// FUNCION QUE ACTUALIZA UNA INCIDENCIA ////
    function updateIncidencia($id, $data){
        
        // Revisar si el array de la base de datos esta cargado y sino cargarlo //
        if(empty($this->dbData)){
            $dbData = $this->cargarDb();
        } else {
            $dbData = $this->dbData;
        }

        // buscar incidencia y cambiar sus valores
        $encontrada = false;
        // Comprobar que ha cargado la base de datos correctamente
        if(!$dbData === false){
            foreach($dbData as $incidencia){
                if($incidencia->id == $id){
                    $incidencia->setTrabajador($data['trabajador']);
                    $incidencia->setHora($data['hora']);
                    $incidencia->setInstalacion($data['instalacion']);
                    $incidencia->setDescripcion($data['descripcion']);

                    header('Content-Type: application/json');
                    http_response_code(201);
                    echo json_encode([
                        "status" => "success",
                        "code" => 201,
                        "time" => date('c'), // Fecha y hora en formato ISO-8601
                        "message" => "Datos con la incidencia MODIFICADA",
                        "data" => $data
                    ]);
                    $encontrada = true;
                    // Llamada a funcion que guarda los cambios
                    $this->guardarDb($dbData);
                    //print_r($dbData);
                    return;
                }
            }
            // control de error no encontrada
            if(!$encontrada){
                header('Content-Type: application/json');
                http_response_code(406);
                echo json_encode([
                    "status" => "Not Found",
                    "code" => 406,
                    "time" => date('c'), // Fecha y hora en formato ISO-8601
                    "message" => "Incidencia no encontrada en la Base de Datos",
                    "data" => $id
                ]);
                return;
            }
        
        }
        
    }

//// FUNCION QUE BORRA UNA INCIDENCIA ////
    function deleteIncidencia($id){
        
        // Revisar si el array de la base de datos esta cargado y sino cargarlo //
        if(empty($this->dbData)){
            $dbData = $this->cargarDb();
        } else {
            $dbData = $this->dbData;
        }
        
        // buscar incidencia
        $encontrada = false;
        // Comprobar que ha cargado la base de datos correctamente
        if(!$dbData === false){
            foreach($dbData as $incidencia){
                if($incidencia->id == $id){

                    header('Content-Type: application/json');
                    http_response_code(200);
                    echo json_encode([
                        "status" => "success",
                        "code" => 204,
                        "time" => date('c'), // Fecha y hora en formato ISO-8601
                        "message" => "Incidencia ELIMINADA",
                        "data" => $id
                    ]);
                    $encontrada = true;
                    // borrar incidencia
                    unset($dbData[$id-1]);
                    // Llamada a funcion que guarda los cambios
                    $this->guardarDb($dbData);
                    return;
                } 
                
            }

            // control error no encontrada se envia id de vuelta solo info
            if(!$encontrada){
                header('Content-Type: application/json');
                http_response_code(406);
                echo json_encode([
                    "status" => "Not Found",
                    "code" => 406,
                    "time" => date('c'), // Fecha y hora en formato ISO-8601
                    "message" => "Incidencia no encontrada en la Base de Datos",
                    "data" => $id
                ]);
                return;
            }
        }
        
    }
/// FUNCION PARA CARGAR LA BASE DE DATOS A UN ARRAY ////
    function cargarDb(){

        $dbData = [];

        // ruta acceso a base de datos
        $dirJson = __DIR__ . '/../models/db/incidenciasDb.json';
        
        // Control de errores //
        if (!file_exists($dirJson)) {
            // Si el archivo no existe, devolver error 404
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "code" => 404,
                "message" => "La base de datos no esta disponible."
            ]);
            return false;
            
        } elseif (file_get_contents($dirJson,true) === false) {
             // Si no se puede leer el archivo, devolver error 500
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "code" => 500,
                "message" => "No se pudo leer el archivo de datos."
            ]);
            return false;
        } else {
            // Como existe y se puede leer, leemos el archivo desde su directorio
        $jsData = file_get_contents($dirJson,true);
        // Si se puede leer el archivo lo decodificamos
        $jsData = json_decode($jsData,true);
        //$dbData = [];

        foreach ($jsData as $incidencia) {
            // Verificar si todas las claves necesarias existen
            
                $id = (int)$incidencia["id"];
                $trabajador = $incidencia["trabajador"];
                $hora = $incidencia["hora"];
                $instalacion = $incidencia["instalacion"];
                $descripcion = $incidencia["descripcion"];

                // Crear un objeto Incidencia
                $incidenciaObj = new Incidencia($id, $trabajador, $hora, $instalacion, $descripcion);

                // Agregar el objeto al array $dbData
                array_push($dbData, $incidenciaObj);
            
        }
        $this->dbData = $dbData;
        return $dbData;
        }
        

    }

    function guardarDb($dbData){
        // Ruta acceso a base de datos
        $dirJson = __DIR__ . '/../models/db/incidenciasDb.json';

        // Convertir el array de objetos a un array serializable para JSON
        $dataToJson = array_map(function($incidencia) {
            return [
                'id' => $incidencia->id,
                'trabajador' => $incidencia->trabajador,
                'hora' => $incidencia->hora,
                'instalacion' => $incidencia->instalacion,
                'descripcion' => $incidencia->descripcion
            ];
        }, $dbData);

        // Convertir a JSON
        $jsonData = json_encode($dataToJson, JSON_PRETTY_PRINT);

        // Grabar nuevos datos a archivo de la base de datos
        file_put_contents($dirJson, $jsonData);
    }

}

<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;





$app->get('/api/rol/getRol', function(Request $request, Response $response){
    $db = new conexion();

    $conn = $db->openConexionDB();

    $sql = "SELECT * FROM roles";

    $result = $conn->query($sql);
 
    if(mysqli_num_rows($result) == 0){
 
     $myArray = [];
     
    }else{
 
     while($row = mysqli_fetch_assoc($result)){
         $myArray[] = array(
             'id_rol' => $row["id_rol"],
             'nombre_rol' => $row["nombre_rol"]
         );
     }
 
    }
    
    
   $salidaJSON = json_encode($myArray);
 
   return $salidaJSON;
  $db->closeConexionDB($conn);


}); 



$app->get('/api/rol/getRolById/{id}', function(Request $request, Response $response){
    $db = new conexion();

    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    // Validamos id
    if(is_numeric($id) == false){
        $id = -1;
    }

    
    $sql = "SELECT * FROM roles where id_rol = " .$id;
    $result = $conn->query($sql);
 
    if(mysqli_num_rows($result) == 0){
 
     $myArray = [];
     
    }else{
 
     while($row = mysqli_fetch_assoc($result)){
         $myArray[] = array(
             'id_rol' => $row["id_rol"],
             'nombre_rol' => $row["nombre_rol"]
         );
     }
 
    }
    
    
   $salidaJSON = json_encode($myArray);
 
   return $salidaJSON;
  $db->closeConexionDB($conn);


}); 


$app->post('/api/rol/new',function (Request $request, Response $response){
    $db = new conexion();
    $validation = new Valida();

    $conn = $db->openConexionDB();

    $nombre_rol = $request->getParsedBody()['nombre_rol'];
   
    //Validamos que no este vacio
    if($validation->isBlank($nombre_rol)){
        echo $response->withStatus(400,"Nombre de rol no puede estar vacio");
    }else{

        $sqlUsuarioExiste = "SELECT * FROM roles where nombre_rol = '$nombre_rol' "; 

        $sql = "INSERT INTO roles (id_rol,nombre_rol) VALUES (null, '$nombre_rol' )";
    
        //Validamos
        try {
            $result = $conn->query($sqlUsuarioExiste);

            if(mysqli_num_rows($result) == 0){
    
                mysqli_query($conn,$sql);
            
                echo $response->withStatus(200,"OK");
            }else{
                echo $response->withStatus(400,"Ya existe un rol con este nombre");
            }
        } catch (\Throwable $th) {
            echo $response->withStatus(400,"Error al ejecutar la consulta");
        }

        
    }
    
  
    $db->closeConexionDB($conn);
    
});

$app->put('/api/rol/updateById',function (Request $request, Response $response){
    $db = new conexion();
    $validation = new Valida();

    $conn = $db->openConexionDB();

    $id_rol = $request->getParsedBody()['id_rol'];
    $nombre_rol = $request->getParsedBody()['nombre_rol'];
   
    //Validamos que no este vacio
    if($validation->isBlank($nombre_rol) || $validation->isBlank($id_rol) ){
        echo $response->withStatus(400,"Campos no pueden estar vacios");
    }else{
        //Validamos
        try {
            $sqlUsuarioExiste = "SELECT * FROM roles where nombre_rol = '$nombre_rol' "; 
            $result = $conn->query($sqlUsuarioExiste);

            if(mysqli_num_rows($result) == 0){
    
                try {

                    $sql = "UPDATE roles set nombre_rol = '$nombre_rol' where id_rol = $id_rol";
                     mysqli_query($conn,$sql);
                     
                    echo $conn->affected_rows;
                    echo $response->withStatus(200,"OK");
                    
                } catch (\Throwable $th) {
                    echo $response->withStatus(400,"Error al ejecutar la consulta");
                }

               
            }else{
                echo $response->withStatus(400,"Ya existe un rol con este nombre");
            }
        } catch (\Throwable $th) {
            echo $response->withStatus(400,"Error al ejecutar la consulta");
        }

        
    }
    
  
    $db->closeConexionDB($conn);
    
});

$app->delete('/api/rol/deleteById/{id}',function (Request $request,Response $response){
    $db = new Conexion();
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    try {
        $sql = "DELETE FROM roles where id_rol = " .$id;
        $result = $conn->query($sql);

        echo $response->withStatus(200,"OK");

    } catch (\Throwable $th) {
        echo $response->withStatus(400,"Error al borrar el rol");
    }

    $db->closeConexionDB($conn);

});


?>
<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


 

$app->get('/api/detalles-usuario',function(Request $request, Response $response){

    $db = new conexion();
        
    $conn = $db->openConexionDB();

    $sql = "SELECT u.id_user,u.email,u.passwd,
    d.id_detalle_usuario,d.nick_user, d.nombre, d.apellido_1, d.apellido_2,
    r.id_rol,r.nombre_rol
    FROM usuario as u
    INNER JOIN detalles_usuario as d
    ON u.id_user = d.id_user
    INNER JOIN roles as r
    ON r.id_rol = d.id_rol";

    $result = $conn->query($sql);
    
    if(mysqli_num_rows($result) == 0){
 
     $myArray = [];
     
    }else{
 
     while($row = mysqli_fetch_assoc($result)){
         $myArray[] = array(
            'id_detalle_usuario' => $row["id_detalle_usuario"],
            'nick_user' => $row["nick_user"],
            'nombre' => $row["nombre"],
            'apellido_1' => $row["apellido_1"],
            'apellido_2' => $row["apellido_2"],
            'usuario' => [
                'id_user' => $row["id_user"],
                'email' => $row["email"],
                'password' => $row["passwd"],
            ],
            'rol' => [
                'id_rol' => $row["id_rol"],
                'nombre_rol' => $row["nombre_rol"],
            ],
           
         );
     }
 
    }
    
    
   $salidaJSON = json_encode($myArray);
 
   return $salidaJSON;
  $db->closeConexionDB($conn);

});


$app->get('/api/detalles-usuarioById/{id}',function(Request $request, Response $response){

    $db = new conexion();
        
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    // Validamos id
    if(is_numeric($id) == false){
        $id = -1;
    }

    $sql = "SELECT u.id_user,u.email,u.passwd,
    d.id_detalle_usuario,d.nick_user, d.nombre, d.apellido_1, d.apellido_2,
    r.id_rol,r.nombre_rol
    FROM usuario as u
    INNER JOIN detalles_usuario as d
    ON u.id_user = d.id_user
    INNER JOIN roles as r
    ON r.id_rol = d.id_rol
    WHERE u.id_user = " .$id;

    $result = $conn->query($sql);
 
    if(mysqli_num_rows($result) == 0){
 
     $myArray = [];
     
    }else{
 
        while($row = mysqli_fetch_assoc($result)){
            $myArray[] = array(
               'id_detalle_usuario' => $row["id_detalle_usuario"],
               'nick_user' => $row["nick_user"],
               'nombre' => $row["nombre"],
               'apellido_1' => $row["apellido_1"],
               'apellido_2' => $row["apellido_2"],
               'usuario' => [
                   'id_user' => $row["id_user"],
                   'email' => $row["email"],
                   'password' => $row["passwd"],
               ],
               'rol' => [
                   'id_rol' => $row["id_rol"],
                   'nombre_rol' => $row["nombre_rol"],
               ],
              
            );
        }
 
    }
    
    
   $salidaJSON = json_encode($myArray);
 
   return $salidaJSON;
  $db->closeConexionDB($conn);

});

$app->post('/api/detalles-usuario/new',function(Request $request, Response $response){

    $validation = new Valida();
    $db = new Conexion();
    $conn = $db->openConexionDB();
    
    $id_user = $request->getParsedBody()['id_user'];
    $nick_user = $request->getParsedBody()['nick_user'];
    $id_rol = $request->getParsedBody()['id_rol'];
    $nombre = $request->getParsedBody()['nombre'];
    $apellido_1 = $request->getParsedBody()['apellido_1'];
    $apellido_2 = $request->getParsedBody()['apellido_2'];


    // Validar
    

    $sql = "INSERT INTO detalles_usuario (id_detalle_usuario,id_user,nick_user,id_rol,nombre,apellido_1,apellido_2)
     VALUES (null, $id_user , '$nick_user', $id_rol,'$nombre','$apellido_1','$apellido_2')";
        
    try {
        mysqli_query($conn,$sql);
        
        echo $response->withStatus(200,"OK");

    } catch (\Throwable $th) {
        echo $response->withStatus(400,"No se ha podido crear el usuario");
    }
 

    $db->closeConexionDB($conn);
});



?>
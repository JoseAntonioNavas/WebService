<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


 


$app->get('/api/usuario/getUsuarios', function(Request $request, Response $response){
            $db = new conexion();
        
            $conn = $db->openConexionDB();
        
        
       
            $sql = "SELECT * FROM usuario";
        
            $result = $conn->query($sql);
         
            if(mysqli_num_rows($result) == 0){
         
             $myArray = [];
             
            }else{
         
             while($row = mysqli_fetch_assoc($result)){
                 $myArray[] = array(
                     'id_user' => $row["id_user"],
                     'email' => $row["email"],
                     'passwd' => $row["passwd"],
                 );
             }
         
            }
            
            
           $salidaJSON = json_encode($myArray);
         
           return $salidaJSON;
          $db->closeConexionDB($conn);
        
        
}); 


$app->get('/api/usuario/getUsuarioById/{id}', function(Request $request, Response $response){
            $db = new conexion();
        
            $conn = $db->openConexionDB();
        
        
            $id = $request->getAttribute('id');
            $sql = "SELECT * FROM usuario WHERE id_user = " .$id;
        
            $result = $conn->query($sql);
         
            if(mysqli_num_rows($result) == 0){
         
             $myArray = [];
             
            }else{
         
             while($row = mysqli_fetch_assoc($result)){
                 $myArray[] = array(
                     'id_user' => $row["id_user"],
                     'email' => $row["email"],
                     'passwd' => $row["passwd"],
                 );
             }
         
            }
            
            
           $salidaJSON = json_encode($myArray);
         
           return $salidaJSON;
          $db->closeConexionDB($conn);
        
        
}); 


$app->get('/api/usuario/getUsuarioByEmail/{email}', function(Request $request, Response $response){
        $db = new conexion();
    
        $conn = $db->openConexionDB();
    
        $email = $request->getAttribute('email');
        $sql = "SELECT * FROM usuario WHERE email =  '" . $email. "'";
    
        $result = $conn->query($sql);
        if(mysqli_num_rows($result) == 0){
     
         $myArray = [];
         
        }else{
     
         while($row = mysqli_fetch_assoc($result)){
             $myArray[] = array(
                 'id_user' => $row["id_user"],
                 'email' => $row["email"],
                 'passwd' => $row["passwd"],
             );
         }
     
        }
        
        
       $salidaJSON = json_encode($myArray);
     
       echo $salidaJSON;
       
      $db->closeConexionDB($conn);
    
    
});
    

$app->post('/api/usuario/new', function(Request $request,Response $response){
            $validation = new Valida();
            $db = new Conexion();
            $conn = $db->openConexionDB();
                    
            $email = $request->getParsedBody()['email'];
            $password = $request->getParsedBody()['passwd'];

            $sql1 = "SELECT * FROM usuario WHERE email =  '" . $email. "'";
    
            $resultEmail = $conn->query($sql1);
    
           // Validamos
            if(($validation->validateEmail($email) == false)){

                echo $response->withStatus(400,"Email no válido");

            }elseif($validation->isBlank($password)){

                echo $response->withStatus(400,"La contraseña es obligatoria");

            }elseif( mysqli_num_rows($resultEmail) != 0){

                echo $response->withStatus(400,"Email ya registrado");

            }else{
                $sql = "INSERT INTO usuario (id_user,email,passwd) VALUES (null, '$email' , '$password')";
        
                try {
                    mysqli_query($conn,$sql);
                    
                    echo $response->withStatus(200,"ok");

                } catch (\Throwable $th) {
                    echo $response->withStatus(400,"No se ha podido crear el usuario");
                }
             
            
            }
        
            $db->closeConexionDB($conn);
        
});
        
$app->delete('/api/usuario/deleteById/{id}' ,function (Request $request,Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    try {
        $sql = "DELETE FROM usuario where id_user = " .$id;
        $result = $conn->query($sql);

        echo $response->withStatus(200,"OK");

    } catch (\Throwable $th) {
        echo $response->withStatus(400,"Error al borrar el usuario");
    }

    $db->closeConexionDB($conn);

});


$app->put('/api/usuario/updatePassword',function (Request $request, Response $response){

    $db = new Conexion();

    $conn = $db->openConexionDB();

    $id = $request->getParsedBody()['id_user'];
    $password = $request->getParsedBody()['passwd'];


    try {
            $sql = "UPDATE usuario SET passwd = '".$password."' WHERE id_user = " .$id;
            $result = $conn->query($sql);
            
            echo $response->withStatus(200,"OK");

        } catch (\Throwable $th) {
            
            echo $response->withStatus(400,"Error al actualizar el usuario");
        }
      
    

    $db->closeConexionDB($conn);
});

$app->post('/api/usuario/login', function(Request $request, Response $response){
 
    
       $db = new conexion();
   
       $conn = $db->openConexionDB();
   
       $email = $request->getParsedBody()['email'];
       $password = $request->getParsedBody()['passwd'];
   
       $sql = "SELECT * FROM usuario WHERE email =  '".$email. "' AND passwd = '".$password."' ";

       $result = $conn->query($sql);   
       
       if(mysqli_num_rows($result) == 0){
    
        $myArray = [];
        
       }else{
    
        while($row = mysqli_fetch_assoc($result)){
            $myArray[] = array(
                'id_user' => $row["id_user"],
                'email' => $row["email"],
                'passwd' => $row["passwd"],
               
            );
        }
    
       }
       
       
      $salidaJSON = json_encode($myArray);
    
      echo $salidaJSON;
      
     $db->closeConexionDB($conn);
   
   
   });



?>
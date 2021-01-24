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

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Email no válido"
                );
                echo json_encode($myArray);

            }elseif($validation->isBlank($password)){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Clave obligatoria"
                );
                echo json_encode($myArray);
               

            }elseif( mysqli_num_rows($resultEmail) != 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Este Email ya ha sido registrado"
                );
                echo json_encode($myArray);
                

            }else{
                $sql = "INSERT INTO usuario (id_user,email,passwd) VALUES (null, '$email' , '$password')";
        
                try {
                    mysqli_query($conn,$sql);
                    
                    $myArray[] = array(
                        'status' => $response->getStatusCode(),
                        'msg' => "OK"
                    );
                    echo json_encode($myArray);

                } catch (\Throwable $th) {

                    $myArray[] = array(
                        'status' => $response->getStatusCode(),
                        'msg' => "Error en la consulta"
                    );
                    echo json_encode($myArray);
                    
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

        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "OK"
        );
        echo json_encode($myArray);
 

           //URL BORRAR DETALLE-USUARIO
           $urlDeleteUsuario = "http://localhost/vehiculosAPI/WebService/public/api/detalles-usuario/deleteByIdUser/".$id;
           var_dump(header("Location: ".$urlDeleteUsuario.""));
           die();

    } catch (\Throwable $th) {

        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Error al hacer la consulta"
        );
        echo json_encode($myArray);

       
    }

    $db->closeConexionDB($conn);

});


$app->put('/api/usuario/updatePassword',function (Request $request, Response $response){

    $validation = new Valida();
    $db = new Conexion();

    $conn = $db->openConexionDB();

    $id = $request->getParsedBody()['id_user'];
    $password = $request->getParsedBody()['passwd'];


    if($validation->isBlank($password)){

        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Clave obligatoria"
        );
        echo json_encode($myArray);
        

    }else{
        try {
            $sql = "UPDATE usuario SET passwd = '".$password."' WHERE id_user = " .$id;
            $result = $conn->query($sql);
            
           
            $myArray[] = array(
                'status' => $response->getStatusCode(),
                'msg' => "OK"
            );
            echo json_encode($myArray);

        } catch (\Throwable $th) {
            
           
        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Error al hacer la consulta"
        );
        echo json_encode($myArray);
        }
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
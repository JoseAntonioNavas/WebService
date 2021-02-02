<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// GET Marca 
$app->post('/api/marca/getMarcas',function(Request $request,Response $response){
    $validation = new Valida();
    $db = new conexion();
    $conn = $db->openConexionDB();

    // PARAMETROS DE BUSQUEDA

    $ByNombreMarca = $request->getParsedBody()['nombre_marca'];
    $visible = $request->getParsedBody()['visible'];

    if($visible == "-1"){
        $visible = "LIKE '%'";
    }else{
        $visible = " = ".$visible;
    }


    $sql = "SELECT * FROM marca  WHERE nombre_marca like '%$ByNombreMarca%' 
    AND visible  $visible";

var_dump($sql);
    $result = $conn->query($sql);

    if(mysqli_num_rows($result) == 0){
         
             $myArray = [];
             
            }else{
         
             while($row = mysqli_fetch_assoc($result)){
                 $myArray[] = array(
                    'id_marca' => $row["id_marca"],
                    'nombre_marca' => $row["nombre_marca"],
                    'visible' => $row["visible"],
                 );
             }
         
            }
      
    $salidaJSON = json_encode($myArray);     
    return $salidaJSON;

    $db->closeConexionDB($conn);  
}); 

// DELETE
$app->delete('/api/marca/deleteById/{id}',function(Request $request,Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    
    try {
        $sql = "DELETE FROM marca where id_marca = " .$id;
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
        
    }

    exit();
    
});


// NUEVA MARCA

$app->post('/api/marca/new',function(Request $request,Response $response){
    $db = new conexion();
    $validation = new Valida();

    $conn = $db->openConexionDB();

    // OBJETO MODELO PASADO POR PARAMETROS
  
    $nombre_marca = $request->getParsedBody()['nombre_marca'];
    $id_marca = $request->getParsedBody()['id_marca'];
    $visible = $request->getParsedBody()['visible'];

    //Validamos que no este vacio
    if($validation->isBlank($id_marca) || is_numeric($id_marca) == false || is_int($id_marca) == false || $validation->isBlank($nombre_marca)){
        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Datos enviados no válidos"
        );
        echo json_encode($myArray);
    }else{

        $sqlMarcaExiste = "SELECT * FROM marca where id_marca = $id_marca OR nombre_marca = '$nombre_marca' "; 

        $sql = "INSERT INTO marca (id_marca,nombre_marca,visible) VALUES ($id_marca,'$nombre_marca',$visible)";
    
        //Validamos
        try {
            
            $result = $conn->query($sqlMarcaExiste);// VALIDAMOS QUE INTRODUCIMOS UNA MARCA YA REGISTRADA EN NUESRTA BASE DE DATOS

            if(mysqli_num_rows($result) != 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Marca ya registrada"
                );
                echo json_encode($myArray);


            }else{
                mysqli_query($conn,$sql); // EJECUTAMOS
        
                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "OK"
                );
                echo json_encode($myArray);
            }

        } catch (\Throwable $th) {

            $myArray[] = array(
                'status' => $response->getStatusCode(),
                'msg' => "Error al ejecutar la consulta"
            );
            echo json_encode($myArray);
        }

        
    }
    
  
    $db->closeConexionDB($conn);
});


//OBTENER TODOS LOS VEHICULOS 
$app->get('/api/vehiculos/getAllMarcas', function(Request $Request, Response $response){

    $res  = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/GetAllMakes?format=JSON');
    $objeto =  json_decode($res);
    echo json_encode($objeto->{'Results'});
    //echo json_encode($objeto->{'Results'});
  

});


?>
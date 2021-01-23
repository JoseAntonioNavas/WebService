<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;
 

//OBTENER TODOS LOS VEHICULOS 
$app->get('/api/vehiculos/getVehiculos', function(Request $Request, Response $response){

    $res  = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/GetAllMakes?format=JSON');
    $objeto =  json_decode($res);
    
    echo json_encode($objeto->{'Results'});
  

});


//OBTENER TODOS LOS modelo POR NOMBRE
$app->get('/api/vehiculos/getModelosByName/{name}', function(Request $request, Response $response){

    $name = $request->getAttribute('name');

    $res  = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMake/'.$name.'?format=json');
    $objeto =  json_decode($res);

    echo json_encode($objeto->{'Results'});
    
  

});

//OBTENER TODOS LOS modelos POR ID
$app->get('/api/vehiculos/getModelosById/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $res  = file_get_contents('https://vpic.nhtsa.dot.gov/api//vehicles/GetModelsForMakeId/'.$id.'?format=json
    ');
    $objeto =  json_decode($res);

    echo json_encode($objeto->{'Results'});

});


//OBTENER VEHICULOS POR NOMBRE Y AÑO
$app->post('/api/vehiculos/getVehiculosByNameAndYear', function(Request $request, Response $response){

    $name = $request->getParsedBody()['name'];
    $year = $request->getParsedBody()['year'];

    $res  = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeYear/make/'.$name.'//modelyear/'.$year.'?format=json');
    $objeto =  json_decode($res);

    echo json_encode($objeto->{'Results'});
    

});

//obtener por idmarca y año
$app->post('/api/vehiculos/getVehiculosBymakeIdAndYear', function(Request $request, Response $response){

    $id_marca = $request->getParsedBody()['id_marca'];
    $year = $request->getParsedBody()['year'];

    $res  = file_get_contents('https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeIdYear/makeId/'.$id_marca.'/modelyear//'.$year.'?format=json');
    $objeto =  json_decode($res);

    echo json_encode($objeto->{'Results'});
    

});


$app->get('/api/vehiculos/getVehiculosBBDD',function(Request $request,Response $response){

    $db = new conexion();
    
    $conn = $db->openConexionDB();

    $sql = "SELECT * FROM vehiculo";

    $result = $conn->query($sql);
    if(mysqli_num_rows($result) == 0){
 
     $myArray = [];
     
    }else{
 
     while($row = mysqli_fetch_assoc($result)){
         $myArray[] = array(
             'id_vehiculo' => $row["id_vehiculo"],
             'id_marca' => $row["id_marca"],
             'id_modelo' => $row["id_modelo"],
             'matricula' => $row["matricula"],
             'id_color' => $row["id_color"],
         );
     }
 
    }
    
    
   $salidaJSON = json_encode($myArray);
 
   echo $salidaJSON;
   
  $db->closeConexionDB($conn);




});



?>
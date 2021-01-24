<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

 

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


$app->post('/api/vehiculos/getVehiculosBBDD',function(Request $request,Response $response){

    $db = new conexion();
    
    $conn = $db->openConexionDB();

    $ByIdVehiculo = $request->getParsedBody()['id_vehiculo'];
    $ByIdMarca = $request->getParsedBody()['id_marca'];
    $ByNombreMarca = $request->getParsedBody()['nombre_marca'];
    $ByIdModelo = $request->getParsedBody()['id_modelo'];
    $ByNombreModelo = $request->getParsedBody()['nombre_modelo'];
    $ByMatricula = $request->getParsedBody()['matricula'];
    $BynombreColor = $request->getParsedBody()['nombre_color'];

    var_dump(is_numeric($ByIdVehiculo));
    // VALIDAMOS
    if( is_numeric($ByIdVehiculo) == false){
        $ByIdVehiculo = '%';
    } 
    if( is_numeric($ByIdMarca) == false){
        $ByIdMarca = '%';
    } 
    if(is_numeric($ByIdModelo) == false){
        $ByIdModelo = '%';
    } 

    if($ByNombreMarca == ""){
        $ByNombreMarca = '%';
    } 
    if($ByNombreModelo == ""){
        $ByNombreModelo = '%';
    } 
    if($BynombreColor == ""){
        $BynombreColor = '%';
    }
    if($ByMatricula == ""){
        $ByMatricula = '%';
    }

    
    $sql = "SELECT v.id_vehiculo,
    ma.id_marca,ma.nombre_marca,
    mo.id_modelo,mo.nombre_modelo,mo.potencia,
    v.matricula,
    co.id_color,co.nombre_color,co.rgbcolor
    FROM vehiculo as v 
    INNER JOIN marca as ma ON v.id_marca = ma.id_marca 
    INNER JOIN modelo as mo ON v.id_modelo = mo.id_modelo
    INNER JOIN color as co ON v.id_color = co.id_color
    WHERE v.id_vehiculo LIKE '$ByIdVehiculo' 
    AND ma.id_marca LIKE '$ByIdMarca'
    AND ma.nombre_marca LIKE '$ByNombreMarca'
    AND mo.id_modelo LIKE '$ByIdModelo'
    AND mo.nombre_modelo LIKE '$ByNombreModelo'
    AND v.matricula LIKE '$ByMatricula'
    AND co.nombre_color LIKE '$BynombreColor'
    ";
    echo $sql;

    $result = $conn->query($sql);
    if(mysqli_num_rows($result) == 0){
 
     $myArray = [];
     
    }else{
 
     while($row = mysqli_fetch_assoc($result)){
         $myArray[] = array(
             'id_vehiculo' => $row["id_vehiculo"],
             'marca' => [
                'id_marca' => $row["id_marca"],
                'nombre_marca' => $row["nombre_marca"],
            ],
            'modelo' => [
                'id_modelo' => $row["id_modelo"],
                'nombre_modelo' => $row["nombre_modelo"],
                'potencia' => $row["potencia"],
            ],   
             'matricula' => $row["matricula"],
             'color' => [
                'id_color' => $row["id_color"],
                'nombre_color' => $row["nombre_color"],
                'rgbcolor' => $row["rgbcolor"],
            ],   
             
         );
     }
 
    }
    
    
   $salidaJSON = json_encode($myArray);
 
   echo $salidaJSON;
   
  $db->closeConexionDB($conn);




});

$app->delete('/api/vehiculos/deleteById/{id}',function(Request $request,Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    try {
        $sql = "DELETE FROM vehiculo where id_vehiculo = " .$id;
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

    $db->closeConexionDB($conn);
});

$app->post('/api/vehiculos/new', function(Request $request, Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();
            
    $id_marca = $request->getParsedBody()['id_marca'];
    $id_modelo = $request->getParsedBody()['id_modelo'];
    $matricula = $request->getParsedBody()['matricula'];
    $id_color = $request->getParsedBody()['id_color'];


    $sqlMarcaExiste = "SELECT * FROM marca WHERE id_marca = '$id_marca' ";
    $sqlModeloExiste = "SELECT * FROM modelo WHERE id_modelo = '$id_modelo' ";
    $sqlMatriculaExiste = "SELECT * FROM vehiculo WHERE matricula = '$matricula' ";
    $sqlColorExiste = "SELECT * FROM color WHERE id_color = '$id_color' ";


    $resultMarca = $conn->query($sqlMarcaExiste);
    $resultModelo = $conn->query($sqlModeloExiste); 
    $resultMatricula = $conn->query($sqlMatriculaExiste);
    $resultColor = $conn->query($sqlColorExiste);

   // Validamos
    
    if( preg_match('/^[0-9]{4}[A-Z]{3}$/',$matricula) == 0 
    || is_numeric($id_color) == false || is_numeric($id_marca) == false || is_numeric($id_modelo) == false){

        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Datos enviados no validos."
        );
        echo json_encode($myArray);


    }else{

   
        // MARCA
            if(mysqli_num_rows($resultMarca) == 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Marca no registrado."
                );
                echo json_encode($myArray);

                // MODELO
            }elseif(mysqli_num_rows($resultModelo) == 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Modelo no registrado"
                );
                echo json_encode($myArray);
            
                //COLOR
            }elseif( mysqli_num_rows($resultColor) == 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Color no  registrado"
                );
                echo json_encode($myArray);
                
                // MATRICULA
            }elseif(mysqli_num_rows($resultMatricula) != 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Matricula ya existe"
                );
                echo json_encode($myArray);

            }else{
                $sql = "INSERT INTO vehiculo (id_marca,id_modelo,matricula,id_color) VALUES ($id_marca, $id_modelo,'$matricula' , $id_color)";
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
    }

    $db->closeConexionDB($conn);

});


?>
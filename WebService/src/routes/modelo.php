<?php  header('Content-Type: charset=utf-8');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// GET Modelo (Mediante parametros)
$app->post('/api/modelo/getModelos',function(Request $request,Response $response){
    $validation = new Valida();
    $db = new conexion();
    $conn = $db->openConexionDB();

    // PARAMETROS DE BUSQUEDA
    $ByNombreModelo = $request->getParsedBody()['nombre_modelo'];
    $ByIdModelo = $request->getParsedBody()['id_modelo'];
    $ByNombreMarca = $request->getParsedBody()['nombre_marca'];
    $ByIdMarca = $request->getParsedBody()['id_marca'];
    $potenciaMin = $request->getParsedBody()['potenciaMin'];
    $potenciaMax = $request->getParsedBody()['potenciaMax'];

    // VALIDAMOS PARAMETROS POTENCIA

    $potenciaMax = intval($potenciaMax);
    $potenciaMin = intval($potenciaMin);

    $ByIdMarca = intval($ByIdMarca);
    $ByIdModelo = intval($ByIdModelo);
    
    $sql = "SELECT mo.id_modelo,mo.nombre_modelo,ma.id_marca,ma.nombre_marca,mo.potencia 
    FROM modelo as mo INNER JOIN marca as ma WHERE mo.id_marca = ma.id_marca 
    AND mo.nombre_modelo like '%$ByNombreModelo%' 
    AND mo.id_modelo = $ByIdModelo 
    AND ma.nombre_marca like '%$ByNombreMarca%' 
    AND mo.id_marca = $ByIdMarca
    AND mo.potencia >= $potenciaMin AND mo.potencia <= $potenciaMax
    ";
    $result = $conn->query($sql);

            if(mysqli_num_rows($result) == 0){
         
             $myArray = [];
             
            }else{
         
             while($row = mysqli_fetch_assoc($result)){
                 $myArray[] = array(
                    'id_modelo' => $row["id_modelo"],
                    'nombre_modelo' => $row["nombre_modelo"],
                    'marca' => [
                        'id_marca' => $row["id_marca"],
                        'nombre_marca' => $row["nombre_marca"],
                    ],
                    'potencia' => $row["potencia"]

                 );
             }
         
            }
      
    $salidaJSON = json_encode($myArray);     
    return $salidaJSON;

    $db->closeConexionDB($conn);  
}); 


$app->delete('/api/modelo/deleteById/{id}',function(Request $request,Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    try {
        $sql = "DELETE FROM modelo where id_modelo = " .$id;
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
    
});


$app->post('/api/modelo/new',function (Request $request, Response $response){
    $db = new conexion();
    $validation = new Valida();

    $conn = $db->openConexionDB();

    // OBJETO MODELO PASADO POR PARAMETROS
    $id_modelo = $request->getParsedBody()['id_modelo'];
    $nombre_modelo = $request->getParsedBody()['nombre_modelo'];
    $id_marca = $request->getParsedBody()['id_marca'];
    $potencia = $request->getParsedBody()['potencia'];


    //Validamos que no este vacio
    if($validation->isBlank($id_modelo) || is_numeric($id_modelo) == false || is_int($id_modelo) == false || $validation->isBlank($nombre_modelo) 
    || $validation->isBlank($id_marca) || is_numeric($potencia) == false || is_int($potencia) == false || $validation->isBlank($potencia)){
        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Datos enviados no válidos"
        );
        echo json_encode($myArray);
    }else{

        $sqlMarcaExiste = "SELECT * FROM marca where id_marca = '$id_marca' "; 
        $sqlModeloExiste = "SELECT * FROM modelo where nombre_modelo = '$nombre_modelo' OR id_modelo like '$id_modelo' ";

        $sql = "INSERT INTO modelo (id_modelo,nombre_modelo,id_marca,potencia) VALUES ($id_modelo, '$nombre_modelo',$id_marca,$potencia )";
    
        //Validamos
        try {
            
            $result = $conn->query($sqlMarcaExiste);// VALIDAMOS QUE INTRODUCIMOS UNA MARCA YA REGISTRADA EN NUESRTA BASE DE DATOS
            $result1 = $conn->query($sqlModeloExiste); // VALIDAMOS QUE NO INTRODUZCA EL MISMO MODELO 

            if(mysqli_num_rows($result1) != 0){
                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "Este modelo ya está registrado."
                );
                echo json_encode($myArray);
            }else if(mysqli_num_rows($result) == 0){

                $myArray[] = array(
                    'status' => $response->getStatusCode(),
                    'msg' => "No hay ninguna marca registrada en nuestra base de datos"
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


?>
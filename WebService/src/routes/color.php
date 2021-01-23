<?php  header('Content-Type: charset=utf-8');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// GET MODELO-COLOR 
$app->post('/api/marca/getMarcas',function(Request $request,Response $response){
    $validation = new Valida();
    $db = new conexion();
    $conn = $db->openConexionDB();

    // PARAMETROS DE BUSQUEDA

    $ByNombreMarca = $request->getParsedBody()['nombre_marca'];
    $ByIdMarca = $request->getParsedBody()['id_marca'];

    $ByIdMarca = intval($ByIdMarca);

    $sql = "SELECT * FROM marca  WHERE nombre_marca like '%$ByNombreMarca%' 
    AND id_marca = $ByIdMarca";

    $result = $conn->query($sql);

    if(mysqli_num_rows($result) == 0){
         
             $myArray = [];
             
            }else{
         
             while($row = mysqli_fetch_assoc($result)){
                 $myArray[] = array(
                    'id_marca' => $row["id_marca"],
                    'nombre_marca' => $row["nombre_marca"],
                 );
             }
         
            }
      
    $salidaJSON = json_encode($myArray);     
    return $salidaJSON;

    $db->closeConexionDB($conn);  
}); 






?>
<?php  

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// GET MODELO-COLOR 
$app->post('/api/color/getColor',function(Request $request,Response $response){
    $db = new conexion();
    $conn = $db->openConexionDB();

    // PARAMETROS DE BUSQUEDA

    $ByNombreColor = $request->getParsedBody()['nombre_color'];


    $sql = "SELECT * FROM color  WHERE nombre_color like '%$ByNombreColor%'";
    $result = $conn->query($sql);

    if(mysqli_num_rows($result) == 0){
         
             $myArray = [];
             
            }else{
         
             while($row = mysqli_fetch_assoc($result)){
                 $myArray[] = array(
                    'id_color' => $row["id_color"],
                    'nombre_color' => $row["nombre_color"],
                    'rgbcolor' => $row['rgbcolor']
                 );
             }
         
    }
      
    $salidaJSON = json_encode($myArray);     
    return $salidaJSON;

    $db->closeConexionDB($conn);  
}); 

$app->delete('/api/color/deleteById/{id}',function(Request $request,Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();

    $id = $request->getAttribute('id');

    try {
        $sql = "DELETE FROM color where id_color = " .$id;
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


$app->post('/api/color/new',function(Request $request,Response $response){

    $db = new Conexion();
    $conn = $db->openConexionDB();
    
    $rgbcolor = $request->getParsedBody()['rgbcolor'];

    // Validar

    if( preg_match('/rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/',$rgbcolor) == 0){

        $myArray[] = array(
            'status' => $response->getStatusCode(),
            'msg' => "Datos no válidos"
        );
        echo json_encode($myArray);
    }else{

        // Para ver si existe el id_rol y id_usuario
        $sqlColor = "SELECT * FROM color where rgbcolor LIKE '".$rgbcolor."'";
        $result = $conn->query($sqlColor);

        if(mysqli_num_rows($result) != 0){
            $myArray[] = array(
                'status' => $response->getStatusCode(),
                'msg' => "Color ya registrado."
            );
            echo json_encode($myArray);
         
        }else{

            //OBTENER EL NOMBRE DEL RGB
            $res  = file_get_contents('https://www.thecolorapi.com/id?format=json&rgb='.$rgbcolor);
            $objeto =  json_decode($res);
            $nombre_color =  $objeto->{'name'}->{'value'};
            
            $sql = "INSERT INTO color (nombre_color,rgbcolor)
            VALUES ('$nombre_color' , '$rgbcolor' )";
                
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
                        'msg' => "Error al realizar la consulta"
                    );
                    echo json_encode($myArray);
                }

            }
         

        } 
        $db->closeConexionDB($conn);
    
});

$app->get('/api/color/getNameColour/{rgb}',function(Request $request,Response $response){

    $rgb = $request->getAttribute('rgb');

    $res  = file_get_contents('https://www.thecolorapi.com/id?format=json&rgb='.$rgb);
    $objeto =  json_decode($res);
    

    echo $objeto->{'name'}->{'value'};

});



?>
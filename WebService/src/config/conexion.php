<?php
class conexion{


    function openConexionDB(){

        $HOST = "localhost";
        $DBNAME = "concesionariocoches";
        $DBUSER = "root";
        $DBPASS = "";
        $DBPORT = "";
    
        
        $conn = mysqli_connect($HOST,$DBUSER,$DBPASS,$DBNAME);
        mysqli_set_charset($conn,"utf8");
    
        
        return $conn;
    }

    function closeConexionDB($conn){

        mysqli_close($conn);
    }

}
    
    //echo "conectado";
    
 ?>
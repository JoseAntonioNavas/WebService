<?php


require '../vendor/autoload.php';
require '../src/config/conexion.php';
require '../src/Valida.php';

$app = new \Slim\App;

// Usuario (login)
require '../src/routes/usuario.php';

// Detalles Usuario
require '../src/routes/detalle-usuario.php';


// Detalles rol
require '../src/routes/rol.php';


//Vehiculos
require '../src/routes/Vehiculos.php';

// Marca
require '../src/routes/marca.php';

//Modelo
require '../src/routes/modelo.php';

// COLOR
require '../src/routes/color.php';


$app->run();




?>


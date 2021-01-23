<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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

// Detalles rol
require '../src/routes/Vehiculos.php';

// Marca
require '../src/routes/marca.php';

//Modelo
require '../src/routes/modelo.php';
$app->run();


// COLOR
require '../src/routes/color.php';

?>


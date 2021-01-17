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

$app->run();


?>

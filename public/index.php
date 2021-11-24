<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/CabanaController.php';
require_once './controllers/AlquilerController.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/MWParaAutenticar.php';
require_once './middlewares/MWParaAutorizar.php';
require_once './middlewares/JSONMiddleware.php';
require_once './db/AccesoDatos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
// $app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("hola alumnos de los lunes!");
    return $response;
});

// peticiones
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
  });

  $app->group('/logueo', function (RouteCollectorProxy $group) {
    $group->post('/', \UsuarioController::class . ':Loguear');
  });


  $app->group('/cabana', function (RouteCollectorProxy $group) {
    $group->get('[/]', \CabanaController::class . ':TraerTodos');
    $group->get('/{cab}', \CabanaController::class . ':obtenerCabanaId');
    $group->post('/', \CabanaController::class . ':CargarUno')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

  $app->group('/alquiler', function (RouteCollectorProxy $group) {
    $group->get('/{estilo}', \AlquilerController::class . ':CabanaPorEstilo')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
    $group->get('/listar/{nombre}', \AlquilerController::class . ':traerCabanaNombre')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');;
    $group->post('/', \AlquilerController::class . ':CargarUno')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

  $app->group('/borrar', function (RouteCollectorProxy $group) {
    $group->delete('/{cabana}', \CabanaController::class . ':BorrarUno')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

  $app->group('/modificar', function (RouteCollectorProxy $group) {
    $group->put('/{cabana}', \CabanaController::class . ':ModificarUno')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

  $app->group('/descargar', function (RouteCollectorProxy $group) {
    $group->get('[/]', \AlquilerController::class . ':descargaPDF')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

// Run app
$app->run();


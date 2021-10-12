<?php
require __DIR__ . '/../vendor/autoload.php';

use Config\Database;
use Illuminate\Support\Facades\File;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

use App\Controllers\EmpleadoController;
use App\Controllers\UsuarioController;
use App\Controllers\HortalizaController;
use App\Middlewares\JsonMiddleware;

$app = AppFactory::create();
$app->setBasePath("/Sbaglia.Julian/public");
$app->addRoutingMiddleware();

new Database;

try {
    $app->post('[/]', EmpleadoController::class . ":login");
    $app->get('[/]', HortalizaController::class . ":getAll");
    $app->post('/ventas[/]', EmpleadoController::class . ":addVenta");
    $app->get('/pdf/{id}[/]', HortalizaController::class . ":pdf");

    $app->group('/hortalizas', function (RouteCollectorProxy $group) {
        $group->post('[/]', HortalizaController::class . ":add");
        $group->get('/tipo/{tipo}[/]', HortalizaController::class . ":getTipo");
        $group->get('/id/{id}[/]', HortalizaController::class . ":getId");
        $group->delete('/{id}[/]', HortalizaController::class . ":delete");
        $group->post('/update/{id}[/]', HortalizaController::class . ":update");
    });

    $app->group('/usuarios', function (RouteCollectorProxy $group) {
        $group->post('[/]', UsuarioController::class . ":add");
        $group->get('[/]', UsuarioController::class . ":getAll");
    });

    $app->add(new JsonMiddleware);
    $app->run();
} catch (\Throwable $th) {

    echo $th->getMessage();
}

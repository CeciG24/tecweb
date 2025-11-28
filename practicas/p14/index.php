<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/tecweb/practicas/p14');


$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hola Mundo desde Slim Framework v4 funcionando");
    return $response;
});

$app->get('/hola/{nombre}', function (Request $request, Response $response, $args) {
    $nombre = $args['nombre'];
    $response->getBody()->write("Hola, " . $nombre);
    return $response;
});

$app->post('/pruebapost', function (Request $request, Response $response) {
    $reqPo = $request->getParsedBody();
    $val1 = $reqPo['val1'];
    $val2 = $reqPo['val2'];
    $response->getBody()->write("Valores recibidos: " . $val1 . ", " . $val2);
    return $response;
}); 

$app->get('/pruebajson', function (Request $request, Response $response) {
    $data[0]["nombre"]="Cecilia";
    $data[0]["apellido"]="Garcia";
    $data[1]["nombre"]="Juan";
    $data[1]["apellido"]="Perez";
    $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->run();


?>
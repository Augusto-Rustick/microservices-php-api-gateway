<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

// Rotas para documentação Swagger
$app->get('/docs', function (Request $request, Response $response) {
    $swagger_file = __DIR__ . '/../swagger.json';
    if (!file_exists($swagger_file)) {
        $openapi = \OpenApi\Generator::scan([__DIR__ . '/../src']);
        file_put_contents($swagger_file, $openapi->toJson());
    }
    $swagger_ui_html = file_get_contents(__DIR__ . '/../swagger-ui.html');
    $response->getBody()->write($swagger_ui_html);
    return $response->withHeader('Content-Type', 'text/html');
});

$app->get('/swagger.json', function (Request $request, Response $response) {
    $swagger_file = __DIR__ . '/../swagger.json';
    if (!file_exists($swagger_file)) {
        $openapi = \OpenApi\Generator::scan([__DIR__ . '/../src']);
        file_put_contents($swagger_file, $openapi->toJson());
    }
    $response->getBody()->write(file_get_contents($swagger_file));
    return $response->withHeader('Content-Type', 'application/json');
});

// Rotas da API
$app->get('/orders', App\Controllers\OrderController::class . ':getOrders');
$app->post('/orders', App\Controllers\OrderController::class . ':createOrder');

$app->run();

<?php

use App\Middleware\ErrorMiddleware;
use App\Model\Product;
use DI\ContainerBuilder;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(
    __DIR__ . '/../config/settings.php',
    __DIR__ . '/../config/settings.local.php'
);
$builder->addDefinitions(__DIR__ . '/../config/services.php');

$container = $builder->build();

$app = AppFactory::createFromContainer($container);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(new MethodOverrideMiddleware());

// Error handler
$app->add(new ErrorMiddleware($container));

$app->group('/product', function (RouteCollectorProxy $group) use ($container) {
    $prod = new Product($container);

    $group->get('s', function (Request $request, Response $response) use ($container) {
        $data = (new Product($container))->findAll();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-type', 'application/json');
    });
    $group->get('/{id:[0-9]+}', function (Request $request, Response $response) use ($container) {
        $id = (int)$request->getAttribute('id');
        $data = (new Product($container))->findOne($id);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-type', 'application/json');
    });
    $group->map(['POST', 'PUT', 'DELETE'], '[/{id:[0-9]+}]',
        function (Request $request, Response $response) use ($container) {
            $answer = ['result' => 'error'];
            switch ($request->getMethod()) {
                case 'POST':
                    $pr = new Product($container);
                    $data = $request->getParsedBody();
                    if ($pr->save($data)) {
                        $answer = ['result' => 'OK'];
                    }
                    break;
                case 'PUT':
                    $id = (int)$request->getAttribute('id');
                    $data = $request->getParsedBody();
                    $pr = new Product($container, $id);
                    if ($pr->update($data)) {
                        $answer = ['result' => 'OK'];
                    }
                    break;
                case 'DELETE':
                    $id = (int)$request->getAttribute('id');
                    $rows = (new Product($container, $id))->delete();
                    if ($rows == 1)
                        $answer = ['result' => 'OK'];
                    break;
            }

            $response->getBody()->write(json_encode($answer));
            return $response->withHeader('Content-Type', 'application/json');
        });
});


$app->run();

<?php

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/setting.php');

$container = $builder->build();

$app = AppFactory::createFromContainer($container);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(new MethodOverrideMiddleware());


$app->get('/', function (Request $request, Response $response) use ($container) {

    $body = ['response' => 'OK'];

    $response->getBody()->write(json_encode($body));
    $response->withHeader('Content-type', 'application/json');
    return $response;
});

$app->run();

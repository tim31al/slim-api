<?php


namespace App\Middleware;


use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Throwable;

class ErrorMiddleware implements MiddlewareInterface
{
    private ContainerInterface $container;

    /**
     * ErrorMiddleware constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @param Throwable $exception
     * @return ResponseInterface
     */
    public function handleException(Throwable $exception): ResponseInterface
    {
        $this->container->get(Logger::class)->error($exception->getMessage());

        $payload = ['error' => $exception->getMessage()];

        $response = new Response();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response;
    }

}
<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthMiddleware
{
    public function __invoke($request, $handler)
    {
        $jwt = true;

        if (!$jwt) {
            $response = new Response();
            $rta = array("rta" => "Prohibido pasar");

            $response->getBody()->write(json_encode($rta));

            return $response;
        } else {
            $response = $handler->handle($request);
            $existingContent = (string)$response->getBody();

            $respon = new Response();
            $respon->getBody()->write('BEFORE' . $existingContent);

            return $respon;
        }
    }
}

<?php

namespace App\Support\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorUnauthorizedResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::unauthorized()
            ->statusCode(403)
            ->description('Unauthorized.');
    }
}

<?php

namespace App\Support\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorNotFoundResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::notFound()->description('Not found resource.');
    }
}

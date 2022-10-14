<?php

declare(strict_types=1);

namespace App\Support\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorUnauthenticatedResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        $response = Schema::object()
            ->properties(
                Schema::string('message')
                    ->required()
                    ->example('Unauthenticated.'),
                Schema::string('type')->required()->example('AuthenticationException'),
                Schema::integer('status')->required()->example(401)
            );

        return Response::create('ErrorUnauthenticated')
            ->statusCode(401)
            ->description('Authentication Exception')
            ->content(MediaType::json()->schema($response));
    }
}

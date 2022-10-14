<?php

declare(strict_types=1);

namespace App\Support\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Header;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorInternalServerErrorResponse extends ResponseFactory implements Reusable
{
    public function build(): Response
    {
        $response = Schema::object()
            ->properties(
                Schema::string('message')->example('Something went wrong.'),
                Schema::string('type')->example('GenericException'),
                Schema::integer('status')->example(500),
                Schema::object('exception')->description('Not present in production environment.')
            );

        return Response::create('ErrorInternalServerError')
            ->description('Something went wrong on our side.')
            ->content(
                MediaType::json()->schema($response)
            )
            ->headers(Header::create('X-Request-ID')->schema(Schema::string()->example('c243d2b8fdcd80626c18f75995d0421b')));
    }
}

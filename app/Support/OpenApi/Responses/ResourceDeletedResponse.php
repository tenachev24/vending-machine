<?php

namespace App\Support\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ResourceDeletedResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::create('deleted')
            ->statusCode(204)
            ->description('Resource deleted.')
            ->content(
                MediaType::json()->schema(
                    Schema::object()
                        ->properties(
                            Schema::object('meta')
                                ->properties(
                                    Schema::integer('timestamp'),
                                ),
                        )
                        ->example([
                            'meta' => [
                                'timestamp' => 1658751325809,
                            ],
                        ])
                )
            );
    }
}

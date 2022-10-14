<?php

namespace App\Support\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class ErrorValidationResponse extends ResponseFactory
{
    public function build(): Response
    {
        $response = Schema::object()->properties(
            Schema::object('data')
                ->properties(
                    Schema::string('message')->example('The given data was invalid.'),
                    Schema::object('errors')
                        ->additionalProperties(
                            Schema::array()->items(Schema::string())
                        )
                        ->example(['field' => ['Something is wrong with this field!']])
                ),
            Schema::object('meta')
                ->properties(Schema::integer('timestamp')->example(1658751325809))
        );

        return Response::create('ErrorValidation')
            ->statusCode(422)
            ->description('Validation errors')
            ->content(
                MediaType::json()->schema($response)
            );
    }
}

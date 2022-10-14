<?php

declare(strict_types=1);

namespace App\Infrastructure\Laravel\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class FormValidationException extends ValidationException
{
    public $validator;

    public $status = Response::HTTP_UNPROCESSABLE_ENTITY;

    /**
     * Create a new exception instance.
     *
     * @param  Validator  $validator
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  string  $errorBag
     * @return void
     */
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator);

        $this->response = $response;
        $this->errorBag = $errorBag;
        $this->validator = $validator;
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'data' => [
                'message' => 'The given data was invalid.',
                'errors' => $this->validator->errors()->messages(),
            ],
            'meta' => ['timestamp' => intdiv((int) now()->format('Uu'), 1000)],
        ], $this->status);
    }
}

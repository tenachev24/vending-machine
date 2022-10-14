<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Infrastructure\Laravel\Exceptions\FormValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class FormRequest extends LaravelFormRequest
{
    /**
     * @param Validator $validator
     * @return void
     * @throws FormValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new FormValidationException($validator);
    }
}

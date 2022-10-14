<?php

declare(strict_types=1);

namespace App\Support;

use Exception;
use Illuminate\Support\Arr;

final class ExceptionFormat
{
    /**
     * @param Exception $exception
     * @return string
     */
    public static function log(Exception $exception): string
    {
        $message = 'File:'.$exception->getFile().PHP_EOL;
        $message .= 'Line:'.$exception->getLine().PHP_EOL;
        $message .= 'Message:'.$exception->getMessage().PHP_EOL;
        $message .= 'Stacktrace:'.PHP_EOL;
        $message .= $exception->getTraceAsString();

        return $message;
    }

    /**
     * @param mixed $exception
     * @return array
     */
    public static function toArray(mixed $exception): array
    {
        return [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'stacktrace' => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }
}

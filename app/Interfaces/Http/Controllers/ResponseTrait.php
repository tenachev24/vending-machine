<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Support\ExceptionFormat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

trait ResponseTrait
{
    /**
     * The current path of resource to respond.
     *
     * @var string
     */
    protected string $resourceItem;

    /**
     * The current path of collection resource to respond.
     *
     * @var string
     */
    protected string $resourceCollection;

    protected function respondWithCustomData($data, string $message = null, $status = 200): JsonResponse
    {
        return new JsonResponse([
            'message' => $message,
            'data' => $data,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()],
        ], $status);
    }

    protected function getTimestampInMilliseconds(): int
    {
        return intdiv((int) now()->format('Uu'), 1000);
    }

    /**
     * Return no content for delete requests.
     */
    protected function respondWithNoContent(): JsonResponse
    {
        return new JsonResponse([
            'data' => null,
            'meta' => ['timestamp' => $this->getTimestampInMilliseconds()],
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * Return collection response from the application.
     */
    protected function respondWithCollection(Collection|EloquentCollection|LengthAwarePaginator $collection)
    {
        return (new $this->resourceCollection($collection))->additional(
            ['meta' => ['timestamp' => $this->getTimestampInMilliseconds()]]
        );
    }

    /**
     * Return single item response from the application.
     */
    protected function respondWithItem(Model $item)
    {
        return (new $this->resourceItem($item))->additional(
            ['meta' => ['timestamp' => $this->getTimestampInMilliseconds()]]
        );
    }

    public function respondWithError(
        string|null     $message = null,
        string|int|null $type = 'GenericException',
        int|null        $status = null,
        mixed           $exception = null,
        array|null      $headers = null,
    ): JsonResponse {
        $headers ??= [];
        $status ??= 500;
        $message ??= 'Something Went Wrong';
        $data = [
            'message' => $message,
            'type' => ! is_null($exception) ? class_basename($exception) : $type,
            'status' => $status,
        ];

        $options = 0;

        if (! app()->environment('production', 'sandbox')) {
            $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

            if (! is_null($exception)) {
                $data['exception'] = ExceptionFormat::toArray($exception);
            }
        }

        $data['meta']['timestamp'] = $this->getTimestampInMilliseconds();

        return (new JsonResponse(data: $data, status: $status, options: $options))
            ->withHeaders($headers);
    }
}

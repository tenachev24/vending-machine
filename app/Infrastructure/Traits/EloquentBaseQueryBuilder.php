<?php

declare(strict_types=1);

namespace App\Infrastructure\Traits;

use Illuminate\Database\Eloquent\Builder;

trait EloquentBaseQueryBuilder
{
    /**
     * @param Builder $query
     * @param array $parameters
     * @param array $related
     * @return Builder
     */
    protected function getSearchQuery(
        Builder $query,
        array   $parameters = ['exact' => [], 'orWhere' => [], 'like' => [], 'whereIn' => []],
        array   $related = ['exact' => [], 'like' => []],
    ): Builder {
        return $query->where(function ($query) use ($parameters, $related) {
            if (isset($related['exact'])) {
                foreach ($related['exact'] as $relation => $value) {
                    $query->whereHas($relation, function ($query) use ($value) {
                        foreach ($value as $filter) {
                            $query->where($filter['column'], $filter['value']);
                        }
                    });
                }
            }

            if (isset($related['like'])) {
                foreach ($related['like'] as $relation => $value) {
                    $query->whereHas($relation, function ($query) use ($value) {
                        foreach ($value as $filter) {
                            $query->where($filter['column'], 'like', '%' . $filter['value'] . '%');
                        }
                    });
                }
            }

            if (isset($parameters['exact'])) {
                foreach ($parameters['exact'] as $field => $value) {
                    $query->where($field, $value);
                }
            }

            if (isset($parameters['orWhere'])) {
                foreach ($parameters['orWhere'] as $field => $value) {
                    $query->orWhere($field, $value);
                }
            }

            if (isset($parameters['like'])) {
                $query->where(function ($query) use ($parameters) {
                    foreach ($parameters['like'] as $field => $value) {
                        $query->orWhere($field, 'like', "%$value%");
                    }
                });
            }

            if (isset($parameters['whereIn']) && $parameters['whereIn']) {
                foreach ($parameters['whereIn'] as $key => $values) {
                    $query->whereIn($key, $values);
                }
            }
        });
    }

    /**
     * @param Builder $query
     * @param array $orderBy
     * @return Builder
     */
    protected function orderBy(Builder $query, array $orderBy = []): Builder
    {
        return $query->when($orderBy, function ($query, $orderBy) {
            $query->orderBy($orderBy['column'], $orderBy['order']);
        });
    }
}

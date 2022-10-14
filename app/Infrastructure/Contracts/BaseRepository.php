<?php

declare(strict_types=1);

namespace App\Infrastructure\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

interface BaseRepository
{
    /**
     * Search by parameters, order by criteria, paginate results
     *
     * @param array $parameters
     * @param array $related
     * @param array $with
     * @param array $orderBy
     * @param int $paginationCount
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function search(
        array $parameters = ['exact' => [], 'orWhere' => [], 'like' => [], 'whereIn' => []],
        array $related = ['exact' => [], 'like' => []],
        array $with = [],
        array $orderBy = [],
        int $paginationCount = 50,
        int $page = 1
    ): LengthAwarePaginator;

    /**
     * @param array $columns
     * @return $this
     */
    public function columns(array $columns = ['*']): self;

    /**
     * Load model relations
     *
     * @param object $model
     * @param array $relations
     * @return Model
     */
    public function load(object $model, array $relations): Model;

    /**
     * Set the relationships of the query.
     *
     * @param array $with
     * @return self
     */
    public function withRelations(array $with = []): self;

    /**
     * Set withoutGlobalScopes attribute to true and apply it to the query.
     *
     * @return self
     */
    public function withoutGlobalScopes(): self;

    /**
     * Save new resource.
     *
     * @param  array  $data
     * @param  bool  $force
     * @return Model
     */
    public function store(array $data, bool $force = false): object;

    /**
     * Updates saved resource.
     *
     * @param  object  $model
     * @param  array  $updateAttributes
     * @return Model
     */
    public function update(object $model, array $updateAttributes): object;

    /**
     * Find single resource by criteria.
     *
     * @param  array  $criteria
     * @return object
     */
    public function findOneBy(array $criteria): object;

    /**
     * Get New Query Builder instance.
     *
     * @return object
     */
    public function newQueryBuilder(): object;

    /**
     * @param  array  $criteria
     * @return object
     */
    public function findWhere(array $criteria): object;

    /**
     * @param object $model
     * @param array $relations
     * @return object
     */
    public function loadRelations(object $model, array $relations): object;

    /**
     * Delete related models
     *
     * @param Model $model
     * @param array $relations ['modelRelation']
     * @return Model
     */
    public function deleteRelatedModels(Model $model, array $relations): Model;

    /**
     * Sync related
     *
     * @param Model $model
     * @param array $relatedIds
     * @param string $relation
     * @return Model
     */
    public function syncRelated(Model $model, array $relatedIds, string $relation): Model;

    /**
     * Delete a model.
     *
     * @param Model $model
     * @return void
     */
    public function deleteOne(Model $model): void;

    /**
     * Get the ids of related models
     *
     * @param Model $model
     * @param string $relation
     * @param string $column
     * @return Collection
     */
    public function pluckRelatedModelsColumn(Model $model, string $relation, string $column): SupportCollection;
}

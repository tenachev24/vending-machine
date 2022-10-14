<?php

declare(strict_types=1);

namespace App\Infrastructure\Abstracts;

use App\Infrastructure\Contracts\BaseRepository;
use App\Infrastructure\Traits\EloquentBaseQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

abstract class EloquentBaseRepository implements BaseRepository
{
    use EloquentBaseQueryBuilder;

    protected array $with = [];

    protected array $columns = ['*'];

    protected bool $withoutGlobalScopes = false;

    public function __construct(public Model $model)
    {
    }

    /**
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
    ): LengthAwarePaginator {
        return $this->orderBy(
            $this->getSearchQuery($this->model->query(), $parameters, $related),
            $orderBy
        )->with($with)->paginate(perPage: $paginationCount, page: $page);
    }

    /**
     * @inheritdoc
     */
    public function store(array $data, bool $force = false): Model
    {
        return $force ?
            $this->model->forceCreate($data) :
            $this->model->create($data);
    }

    /**
     * @inheritdoc
     */
    public function update(object $model, array $updateAttributes): Model
    {
        /** @var Model $modelToBeUpdated */
        $modelToBeUpdated = $model;

        return tap($modelToBeUpdated)->update($updateAttributes);
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function columns(array $columns = ['*']): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param object $model
     * @param array $relations
     * @return Model
     */
    public function load(object $model, array $relations): Model
    {
        $model->load($relations);

        return $model;
    }

    /**
     * @param array $with
     * @return $this
     */
    public function withRelations(array $with = ['*']): self
    {
        $this->with = $with;

        return $this;
    }

    /**
     * @return Builder
     */
    public function newQueryBuilder(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @inheritdoc
     */
    public function withoutGlobalScopes(): self
    {
        $this->withoutGlobalScopes = true;

        return $this;
    }

    /**
     * @param  array  $criteria
     * @return Model
     */
    public function findOneBy(array $criteria): Model
    {
        return $this->where($criteria)->firstOrFail($this->columns);
    }

    /**
     * @param array $criteria
     * @return Builder
     */
    private function where(array $criteria): Builder
    {
        if (! $this->withoutGlobalScopes) {
            return $this->model->newQuery()
                ->where($criteria)
                ->with($this->with)
                ->orderByDesc('created_at');
        }

        return $this->model->newQuery()
            ->withoutGlobalScopes()
            ->where($criteria)
            ->with($this->with)
            ->orderByDesc('created_at');
    }

    /**
     * @param array $criteria
     * @return Collection
     */
    public function findWhere(array $criteria): Collection
    {
        return $this->where($criteria)->get($this->columns);
    }

    /**
     * @param object $model
     * @param array $relations
     * @return object
     */
    public function loadRelations(object $model, array $relations): object
    {
        $modelToBeUpdated = $model;

        return tap($modelToBeUpdated)->load($relations);
    }

    /**
     * @param Model $model
     * @param array $relations
     * @return Model
     */
    public function deleteRelatedModels(Model $model, array $relations): Model
    {
        foreach ($relations as $relation) {
            $model->$relation()->delete();
        }

        return $model;
    }

    /**
     * @param Model $model
     * @param array $relatedIds
     * @param string $relation
     * @return Model
     */
    public function syncRelated(Model $model, array $relatedIds, string $relation): Model
    {
        $model->$relation()->sync($relatedIds);
        $model->load($relation);

        return $model;
    }

    /**
     * @param Model $model
     * @return void
     */
    public function deleteOne(Model $model): void
    {
        $model->delete();
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param string $column
     * @return SupportCollection
     */
    public function pluckRelatedModelsColumn(Model $model, string $relation, string $column): SupportCollection
    {
        return $model->$relation()->pluck($column);
    }
}

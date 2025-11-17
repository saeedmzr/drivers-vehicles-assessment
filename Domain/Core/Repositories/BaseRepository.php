<?php

namespace Domain\Core\Repositories;

use Domain\Core\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    protected Builder $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    public function find($id)
    {
        return $this->query->find($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function get()
    {
        return $this->query->get();
    }

    public function getEntities(): array
    {
        return $this->get()->map(function ($model) {
            return $model->toEntity();
        })->toArray();
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $instance = $this->find($id);
        if ($instance) {
            $instance->update($attributes);
        }
        return $instance;
    }

    public function delete($id)
    {
        $instance = $this->find($id);
        if ($instance) {
            $instance->delete();
        }
        return $instance;
    }

    public function filtering(array $filters): self
    {
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                // support for advanced conditions: ['column', 'operator', 'value']
                if (count($value) === 3) {
                    $this->query->where($value[0], $value[1], $value[2]);
                }
            } else {
                $this->query->where($key, $value);
            }
        }
        return $this;
    }

    public function ordering(string $column, string $direction = 'asc'): self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function paginating(int $perPage = 15, array $columns = ['*'])
    {
        return $this->query->paginate($perPage, $columns);
    }

    /**
     * Search across multiple columns
     */
    public function searching(string $search, array $columns): self
    {
        $this->query->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', "%{$search}%");
            }
        });
        return $this;
    }

    /**
     * Add relation count
     */
    public function withRelationCount(string $relation): self
    {
        $this->query->withCount($relation);
        return $this;
    }

    /**
     * Filter by having a relation
     */
    public function hasRelation(string $relation): self
    {
        $this->query->has($relation);
        return $this;
    }

    /**
     * Filter by not having a relation
     */
    public function doesntHaveRelation(string $relation): self
    {
        $this->query->doesntHave($relation);
        return $this;
    }

    /**
     * Reset query builder to start fresh
     */
    public function resetQuery(): self
    {
        $this->query = $this->model->newQuery();
        return $this;
    }
}

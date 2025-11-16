<?php

namespace Domain\Core;

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
}

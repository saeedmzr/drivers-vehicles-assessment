<?php

namespace Domain\Core\Usecases;

use Domain\Core\Contracts\BaseRepositoryInterface;
use Domain\Core\Contracts\BaseUsecaseInterface;

abstract class BaseUsecase implements BaseUsecaseInterface
{
    protected BaseRepositoryInterface $repository;

    public function all()
    {
        return $this->repository->findAll();
    }

    public function find($id)
    {
        $model = $this->repository->find($id);
        return $model ? $model->toEntity() : null;
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    public function filter(array $filters)
    {
        return $this->repository->filtering($filters)->getEntities();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginating($perPage);
    }

    public function filterAndPaginate(array $filters = [], int $perPage = 15)
    {
        return $this->repository->filtering($filters)->paginating($perPage);
    }

    public function orderBy(string $column, string $direction = 'asc')
    {
        return $this->repository->ordering($column, $direction)->getEntities();
    }

    public function getEntities(): array
    {
        return $this->repository->getEntities();
    }

    /**
     * Advanced search with pagination
     */
    public function searchAndPaginate(
        ?string $search,
        array $searchColumns,
        ?string $sortBy,
        string $sortDirection,
        ?bool $hasRelation,
        string $relationName,
        int $perPage,
        int $page
    ) {
        // Reset query to start fresh
        $this->repository->resetQuery();

        // Apply search if provided
        if ($search) {
            $this->repository->searching($search, $searchColumns);
        }

        // Apply relation filter if provided
        if ($hasRelation !== null) {
            if ($hasRelation) {
                $this->repository->hasRelation($relationName);
            } else {
                $this->repository->doesntHaveRelation($relationName);
            }
        }

        // Apply sorting
        if ($sortBy) {
            $this->repository->ordering($sortBy, $sortDirection);
        }

        // Return paginated results
        return $this->repository->paginating($perPage, ['*']);
    }

    public function __call(string $method, array $arguments)
    {
        if (!method_exists($this->repository, $method)) {
            throw new \BadMethodCallException(
                sprintf(
                    'Method %s does not exist on repository %s.',
                    $method,
                    get_class($this->repository)
                )
            );
        }

        return $this->repository->{$method}(...$arguments);
    }
}

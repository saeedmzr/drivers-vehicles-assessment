<?php

namespace Domain\Core\Usecases;

use Domain\Core\Contracts\BaseRepositoryInterface;
use Domain\Core\Contracts\BaseUsecaseInterface;

abstract class BaseUsecase implements BaseUsecaseInterface
{
    protected BaseRepositoryInterface $repository;

    /**
     * Get all records
     */
    public function all()
    {
        return $this->repository->findAll();
    }

    /**
     * Find a record by ID
     */
    public function find($id)
    {
        return $this->repository->find($id)->getEntities();
    }

    /**
     * Store/Create a new record
     */
    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    /**
     * Update an existing record
     */
    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a record
     */
    public function destroy($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Filter records with conditions
     */
    public function filter(array $filters)
    {
        return $this->repository->filtering($filters)->getEntities();
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginating($perPage)->getEntities();
    }

    /**
     * Get filtered and paginated records
     */
    public function filterAndPaginate(array $filters = [], int $perPage = 15)
    {
        return $this->repository->filtering($filters)->paginating($perPage)->getEntities();
    }

    /**
     * Get ordered records
     */
    public function orderBy(string $column, string $direction = 'asc')
    {
        return $this->repository->ordering($column, $direction)->getEntities();
    }

    /**
     * Get entities (domain objects)
     */
    public function getEntities(): array
    {
        return $this->repository->getEntities();
    }

    /**
     * Magic method to forward any other calls to repository
     */
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

<?php

namespace Domain\Core\Contracts;

interface BaseRepositoryInterface
{
    public function find($id);

    public function findAll();
    public function get();
    public function getEntities():array;

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function filtering(array $filters): self;

    public function ordering(string $column, string $direction = 'asc'): self;

    public function paginating(int $perPage = 15, array $columns = ['*']);
}

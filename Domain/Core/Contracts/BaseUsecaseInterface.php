<?php

namespace Domain\Core\Contracts;

interface BaseUsecaseInterface
{
    public function all();
    public function find($id);
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
    public function filter(array $filters);
    public function paginate(int $perPage = 15);

    public function searchAndPaginate(
        ?string $search,
        array $searchColumns,
        ?string $sortBy,
        string $sortDirection,
        ?bool $hasRelation,
        string $relationName,
        int $perPage,
        int $page
    );
}

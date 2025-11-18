<?php

namespace Presentation\Vehicle\Requests;

use Presentation\Base\Requests\PaginatedRequest;

readonly class ListVehiclesRequest extends PaginatedRequest
{
    public function __construct(
        ?int $perPage = 15,
        ?int $page = 1,
        public ?string $search = null,
        public ?string $sortBy = 'created_at',
        public ?string $sortDirection = 'desc',
        public ?bool $hasDrivers = null,
    ) {
        parent::__construct($perPage, $page);
    }

    public static function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => ['nullable', 'string', 'max:255'],
            'sort_by' => ['nullable', 'string', 'in:plate_number,make,model,year,created_at'],
            'sort_direction' => ['nullable', 'string', 'in:asc,desc'],
            'has_drivers' => ['nullable', 'boolean'],
        ]);
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function getSortBy(): string
    {
        return $this->sortBy ?? 'created_at';
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection ?? 'desc';
    }

    public function hasDriversFilter(): ?bool
    {
        return $this->hasDrivers;
    }
}

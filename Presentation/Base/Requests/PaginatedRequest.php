<?php

namespace Presentation\Base\Requests;

readonly class PaginatedRequest extends BaseRequest
{
    public function __construct(
        public ?int $perPage = 15,
        public ?int $page = 1,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get items per page
     */
    public function getPerPage(): int
    {
        return $this->perPage ?? 15;
    }

    /**
     * Get page number
     */
    public function getPage(): int
    {
        return $this->page ?? 1;
    }
}

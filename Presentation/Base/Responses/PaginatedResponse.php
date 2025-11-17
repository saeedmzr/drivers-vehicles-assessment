<?php

namespace Presentation\Base\Responses;


readonly class PaginatedResponse extends BaseResponse
{
    public function __construct(
        public array            $items,
        public int              $total,
        public int              $perPage,
        public int              $page,
        public ?string          $updated_at = null,
        public ?string          $name = null,
        public ?string          $description = null,
        public ?SeoMetaResponse $seoMeta = null,
    )
    {
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}

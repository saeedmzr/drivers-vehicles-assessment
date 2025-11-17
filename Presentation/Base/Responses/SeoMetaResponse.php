<?php

namespace Presentation\Base\Responses;

readonly class SeoMetaResponse extends BaseResponse
{

    public function __construct(
        protected ?string $title = null,
        protected ?string $description = null,
    )
    {
    }

    public static function make(array $seoMetaData): static
    {
        return new static(
            title: $seoMetaData['title'],
            description: $seoMetaData['description']
        );
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }


}

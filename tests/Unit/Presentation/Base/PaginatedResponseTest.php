<?php

namespace Tests\Unit\Presentation\Base;

use Illuminate\Support\Facades\Log;
use Presentation\Base\Responses\PaginatedResponse;
use Tests\Unit\UnitTestCase;

class PaginatedResponseTest extends UnitTestCase
{
    /** @test */
    public function it_creates_paginated_response_with_meta(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ];

        $response = new PaginatedResponse(
            items: $items,
            total: 100,
            perPage: 15,
            page: 1
        );

        $this->assertEquals($items, $response->getItems());
        $this->assertEquals(100, $response->getTotal());
        $this->assertEquals(15, $response->getPerPage());
        $this->assertEquals(1, $response->getPage());
    }

    /** @test */
    public function it_handles_zero_items(): void
    {
        $response = new PaginatedResponse(
            items: [],
            total: 0,
            perPage: 15,
            page: 1
        );

        $array = $response->toArray();

        $this->assertEquals(0, $array['total']);
        $this->assertEmpty($array['items']);
    }

    /** @test */
    public function it_includes_optional_fields(): void
    {
        $response = new PaginatedResponse(
            items: [],
            total: 10,
            perPage: 15,
            page: 1,
            updated_at: '2025-11-18T10:00:00Z',
            name: 'Test List',
            description: 'Test Description'
        );

        $array = $response->toArray();

        $this->assertEquals('2025-11-18T10:00:00Z', $array['updated_at']);
        $this->assertEquals('Test List', $array['name']);
        $this->assertEquals('Test Description', $array['description']);
    }
}

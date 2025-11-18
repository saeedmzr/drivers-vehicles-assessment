<?php

namespace Tests\Unit\Presentation\Base;

use Presentation\Base\Responses\BaseResponse;
use Tests\Unit\UnitTestCase;

class BaseResponseTest extends UnitTestCase
{
    /** @test */
    public function it_converts_to_array_with_snake_case(): void
    {
        $response = new TestableResponse(
            firstName: 'John',
            lastName: 'Doe',
            phoneNumber: '+1234567890'
        );

        $array = $response->toArray();

        $this->assertArrayHasKey('first_name', $array);
        $this->assertArrayHasKey('last_name', $array);
        $this->assertArrayHasKey('phone_number', $array);
        $this->assertEquals('John', $array['first_name']);
        $this->assertEquals('Doe', $array['last_name']);
    }

    /** @test */
    public function it_converts_to_json(): void
    {
        $response = new TestableResponse(
            firstName: 'John',
            lastName: 'Doe',
            phoneNumber: '+1234567890'
        );

        $json = $response->toJson();

        $this->assertIsArray($json);
        $this->assertEquals($response->toArray(), $json);
    }

    /** @test */
    public function it_is_arrayable(): void
    {
        $response = new TestableResponse(
            firstName: 'John',
            lastName: 'Doe',
            phoneNumber: '+1234567890'
        );

        $this->assertInstanceOf(\Illuminate\Contracts\Support\Arrayable::class, $response);
    }

    /** @test */
    public function it_is_jsonable(): void
    {
        $response = new TestableResponse(
            firstName: 'John',
            lastName: 'Doe',
            phoneNumber: '+1234567890'
        );

        $this->assertInstanceOf(\Illuminate\Contracts\Support\Jsonable::class, $response);
    }
}

// Testable Response Class
readonly class TestableResponse extends BaseResponse
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $phoneNumber,
    ) {
    }
}

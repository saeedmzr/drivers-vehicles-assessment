<?php

namespace Tests\Unit\Presentation\Base;

use Presentation\Base\Controllers\BaseController;
use Tests\Unit\UnitTestCase;

class BaseControllerTest extends UnitTestCase
{
    private BaseController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new BaseController();
    }

    /** @test */
    public function it_returns_success_response(): void
    {
        $data = ['message' => 'Success'];
        $response = $this->controller->success($data);

        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals($data, $content['data']);
    }

    /** @test */
    public function it_returns_success_response_with_custom_status(): void
    {
        $data = ['message' => 'Created'];
        $response = $this->controller->success($data, status: 201);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_success_response_with_meta(): void
    {
        $data = ['message' => 'Success'];
        $meta = ['page' => 1, 'total' => 100];
        $response = $this->controller->success($data, $meta);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals($meta, $content['meta']);
    }

    /** @test */
    public function it_converts_base_response_to_json(): void
    {
        $baseResponse = new TestableResponse(
            firstName: 'John',
            lastName: 'Doe',
            phoneNumber: '+1234567890'
        );

        $response = $this->controller->success($baseResponse);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('first_name', $content['data']);
    }

    /** @test */
    public function it_returns_failed_response(): void
    {
        $exception = new \Exception('Test error');
        $response = $this->controller->failed($exception, ['message' => 'Error occurred']);

        $this->assertEquals(400, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertFalse($content['success']);
        $this->assertEquals('Error occurred', $content['error']['message']);
    }

    /** @test */
    public function it_returns_failed_response_with_custom_status(): void
    {
        $exception = new \Exception('Not found');
        $response = $this->controller->failed($exception, ['message' => 'Not found'], 404);

        $this->assertEquals(404, $response->getStatusCode());
    }
}

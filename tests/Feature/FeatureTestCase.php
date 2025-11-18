<?php

namespace Tests\Feature;

use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    protected $defaultHeaders = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Make API request with default headers
     */
    protected function apiGet(string $uri, array $headers = [])
    {
        return $this->withHeaders(array_merge($this->defaultHeaders, $headers))
            ->get($uri);
    }

    protected function apiPost($uri, array $data = [], array $headers = [])
    {
        return $this->json('POST', $uri, $data, $headers);
    }

    protected function apiPut(string $uri, array $data = [], array $headers = [])
    {
        return $this->json('PUT', $uri, $data, $headers);
    }

    protected function apiDelete(string $uri, array $headers = [])
    {
        return $this->withHeaders(array_merge($this->defaultHeaders, $headers))
            ->delete($uri);
    }
}

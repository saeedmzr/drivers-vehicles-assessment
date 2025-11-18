<?php

namespace Tests\Integration\Container;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\Integration\IntegrationTestCase;

class DockerConnectionTest extends IntegrationTestCase
{
    /** @test */
    public function it_connects_to_mysql_container(): void
    {
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Failed to connect to MySQL: ' . $e->getMessage());
        }
    }

    /** @test */
    public function it_can_query_database(): void
    {
        $result = DB::select('SELECT 1 as test');
        $this->assertEquals(1, $result[0]->test);
    }

    /** @test */
    public function it_connects_to_redis_container(): void
    {
        try {
            Redis::connection()->ping();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Failed to connect to Redis: ' . $e->getMessage());
        }
    }

    /** @test */
    public function it_can_set_and_get_redis_values(): void
    {
        $key = 'test_key_' . time();
        $value = 'test_value';

        Redis::set($key, $value);
        $retrieved = Redis::get($key);

        $this->assertEquals($value, $retrieved);

        // Cleanup
        Redis::del($key);
    }
}

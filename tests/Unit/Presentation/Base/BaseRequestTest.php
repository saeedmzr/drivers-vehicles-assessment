<?php

namespace Tests\Unit\Presentation\Base;

use Helper\ArrayHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Presentation\Base\Requests\BaseRequest;
use Tests\Unit\UnitTestCase;

class BaseRequestTest extends UnitTestCase
{
    #[Test]
    public function it_creates_request_from_array(): void
    {
        $data = [
            'name' => 'John Doe',
            'license_number' => 'DL123456',
            'phone_number' => '+1234567890',
        ];

        $request = TestableRequest::fromArray($data);

        $this->assertEquals('John Doe', $request->name);
        $this->assertEquals('DL123456', $request->licenseNumber);
        $this->assertEquals('+1234567890', $request->phoneNumber);
    }

    #[Test]
    public function it_converts_snake_case_to_camel_case(): void
    {
        $data = [
            'license_number' => 'DL123456',
            'phone_number' => '+1234567890',
            'name' => 'Test Name', // Add required field
        ];

        $request = TestableRequest::fromArray($data);

        $this->assertEquals('DL123456', $request->licenseNumber);
        $this->assertEquals('+1234567890', $request->phoneNumber);
    }



    #[Test]
    public function it_gets_data_as_array(): void
    {
        $data = [
            'name' => 'John Doe',
            'license_number' => 'DL123456',
            'phone_number' => '+1234567890',
        ];

        $request = TestableRequest::fromArray($data);
        $result = $request->getData();

        $this->assertIsArray($result);
        $this->assertEquals('John Doe', $result['name']);
    }
}

readonly class TestableRequest extends BaseRequest
{
    public function __construct(
        public string $name,
        public string $licenseNumber,
        public string $phoneNumber,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'licenseNumber' => ['required', 'string'],
            'phoneNumber' => ['required', 'string'],
        ];
    }
}

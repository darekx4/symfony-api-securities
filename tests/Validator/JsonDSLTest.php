<?php

namespace App\Tests\Validator;

use App\Constants\Generic;
use App\Exceptions\InvalidPayloadException;
use App\Validator\SecurityJsonDSL;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class JsonDSLTest extends TestCase
{
    public function testWrongData()
    {
        $request = $this->buildRequest('{"foo":"bar"}');

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage(Generic::PROVIDED_JSON_DOES_NOT_CONTAIN_ALL_DATA);

        // assert that your calculator added the numbers correctly!
        SecurityJsonDSL::validate($request);
    }

    public function testWrongOperator()
    {
        $request = $this->buildRequest('{
              "expression": {"fn": "%%%", "a": "price", "b": "eps"},
              "security": "BBIG"
            }');

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage(Generic::PROVIDED_OPERATOR_IS_NOT_VALID);

        // assert that your calculator added the numbers correctly!
        SecurityJsonDSL::validate($request);
    }

    public function testWrongSecurity()
    {
        $request = $this->buildRequest('{
              "expression": {"fn": "*", "a": "sales", "b": 2},
              "security": "AEHL_NOT_KNOWN"
            }');

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage(Generic::PROVIDED_SECURITY_IS_NOT_VALID);

        // assert that your calculator added the numbers correctly!
        SecurityJsonDSL::validate($request);
    }

    public function testWrongAttribute()
    {
        $request = $this->buildRequest('{
              "expression": {"fn": "*", "a": "ATTRIBUTE_NOT_KNOWN", "b": 2},
              "security": "AEHL"
            }');

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage(Generic::PROVIDED_ATTRIBUTES_ARE_NOT_VALID);

        // assert that your calculator added the numbers correctly!
        SecurityJsonDSL::validate($request);
    }

    private function buildRequest($body): Request
    {
        return new Request([], [], [], [], [], ['HTTP_CONTENT_TYPE' => 'application/json'], $body);
    }
}

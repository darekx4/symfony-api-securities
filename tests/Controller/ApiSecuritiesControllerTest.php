<?php

namespace App\Tests\Controller;

use App\Constants\Generic;
use PHPUnit\Framework\TestCase;
use GuzzleHttp;

class ApiSecuritiesControllerTest extends TestCase
{

    private GuzzleHttp\Client $client;

    public function setUp(): void
    {
        $this->client = new GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000/']);
    }

    /*
     |--------------------------------------------------------------------------
     | OK responses - we will use checks by using attributes as integers and expect results as they were simply math calculations
     |--------------------------------------------------------------------------
     */
    public function testSimpleAdd(): void
    {

        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {"fn": "+", "a": 10, "b": 10},
                  "security": "AEHL"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(20, $response->valuation_result);
    }

    public function testSimpleSubtract(): void
    {

        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {"fn": "-", "a": 20, "b": 10},
                  "security": "AEHL"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(10, $response->valuation_result);
    }

    public function testSimpleMultiply(): void
    {

        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {"fn": "*", "a": 10, "b": 10},
                  "security": "AEHL"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(100, $response->valuation_result);
    }

    public function testSimpleDivide(): void
    {

        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {"fn": "/", "a": 1, "b": 10},
                  "security": "AEHL"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(0.10, $response->valuation_result);
    }
    /*
   |--------------------------------------------------------------------------
   | More complex scenarios
   |--------------------------------------------------------------------------
   */
    public function testComplexAddMixed(): void
    {
        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {
                    "fn": "+", 
                    "a": {"fn": "*", "a": 10, "b": 10}, 
                    "b": 2
                  },
                  "security": "EVGO"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(102, $response->valuation_result);
    }

    public function testComplexAddMixed1(): void
    {
        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {
                    "fn": "+", 
                    "a": {"fn": "*", "a": 10, "b": 10}, 
                    "b": {"fn": "/", "a": 10, "b": 10}
                  },
                  "security": "EVGO"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(101, $response->valuation_result);
    }

    public function testComplexMixed(): void
    {
        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {
                    "fn": "/", 
                    "a": {"fn": "*", "a": 10, "b": 10}, 
                    "b": {"fn": "+", "a": 10, "b": 10}
                  },
                  "security": "EVGO"
                }'
        ]);

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(5, $response->valuation_result);
    }


    public function testComplexMixed1(): void
    {
        $request = $this->client->request('POST', 'api/securities/analytics', [
            'body' => '{
                  "expression": {
                    "fn": "/", 
                    "a": {"fn": "*", "a": "2.27", "b": "4.1"}, 
                    "b": {"fn": "+", "a": 10.91, "b": 10.33}
                  },
                  "security": "EVGO"
                }'
        ]);// 0.438182674

        $response = json_decode($request->getBody()->getContents());
        $this->assertEquals(Generic::ASSET_HAS_BEEN_VALUED, $response->status);
        $this->assertEquals(0.40, $response->valuation_result);
    }

    //TODO adding more possible scenarios
}

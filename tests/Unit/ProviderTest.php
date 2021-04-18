<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

use App\Http\Controllers\API\ProviderController;

class ProviderTest extends TestCase
{
    // run vendor\bin\phpunit
    public function testTrueGetResults(){
        $response = $this->json('GET', 'api/v1/users', ['Accept' => 'application/json']);
        $this->assertEquals(200, $response->status());
    }
    public function testNullResults(){
        $this->json('GET', 'api/v1/users?currency=EGP', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
            "message" => "success",
            "data" => []
        ]);
    }
    public function testTrueFilterProviders()
    {
        $this->json('GET', 'api/v1/users?provider=DataProviderX', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "message" => "success",
                "data" => [
                    [
                        "file_name" =>"DataProviderX",
                        "amount" => 200,
                        "currency" => "USD",
                        "status_name" => "authorised"
                    ],
                    [
                        "file_name" =>"DataProviderX",
                        "amount" => 500,
                        "currency" => "USD",
                        "status_name" => "authorised"
                    ]
                ]
            ]);
    }
}

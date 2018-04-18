<?php

namespace Tests;

class MiddlewareTest extends TestCase
{
    /** @test */
    public function using_an_invalid_token_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => 'invalidtoken']);

        $response->assertStatus(401);
    }

    /** @test */
    public function using_no_token_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test');

        $response->assertStatus(401);
    }

    /** @test */
    public function using_a_valid_token_returns_ok()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:test')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(200);
    }

    /** @test */
    public function using_a_non_existant_service_name_always_returns_unauthorised()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken:nottest')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(401);
    }

    /** @test */
    public function using_a_no_service_name_crashes()
    {
        $token = \App\ApiToken::createNew('test');
        \Route::middleware('apitoken')->any('/_test/', function () {
            return 'OK';
        });

        $response = $this->call('GET', '_test', ['api_token' => $token]);

        $response->assertStatus(500);
    }
}
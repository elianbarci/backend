<?php

namespace Tests\Feature;

use App\Models\Planet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PlanetTest extends TestCase
{
    public function test_populate(): void
    {

        $credentials = [
            'email' => env('TEST_USER'),
            'password' => env('TEST_PASS')
        ];

        if (Auth::attempt($credentials)) {

            $access_token = Auth::user()->createToken('authToken')->accessToken;

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->call('POST', '/api/planets/populate');

            $response->assertStatus(200);

            Planet::truncate();


        }

    }

    public function test_opinion(): void
    {

        $credentials = [
            'email' => env('TEST_USER'),
            'password' => env('TEST_PASS')
        ];

        if (Auth::attempt($credentials)) {

            $access_token = Auth::user()->createToken('authToken')->accessToken;

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->call('POST', '/api/planets/populate');

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->call('GET', '/api/planets/opinion', [
                'idPlanet' => "49"
            ]);

            Planet::truncate();

            $response->assertStatus(200);
        }
    }
}

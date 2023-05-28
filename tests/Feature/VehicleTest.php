<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class VehicleTest extends TestCase
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
            ])->call('POST', '/api/vehicles/populate');

            $response->assertStatus(200);
          
            Vehicle::truncate();

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
            ])->call('POST', '/api/vehicles/populate');

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->call('GET', '/api/vehicles/opinion', [
                'idCharacter' => "49"
            ]);

            Vehicle::truncate();

            $response->assertStatus(200);
        }
    }
}

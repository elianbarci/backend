<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    public function test_populate(): void
    {

        //En este caso si se seedeo bien la DB el usuario utilizado para testing sería "Testing User"
        //hay varias formas de hacer esto como por ejemplo buscar el usuario Testing por nombre o utilizar las creds
        //que estan en .env.example

        if (Auth::loginUsingId(1)) {

            $access_token = Auth::user()->createToken('authToken')->accessToken;

            $response = $this->post('/api/vehicles/populate', [], [    
                'Accept' => 'application/json' ,
                'Authorization' => 'Bearer ' . $access_token
            ]);

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

            $response = $this->post('/api/vehicles/populate', [], [    
                'Accept' => 'application/json' ,
                'Authorization' => 'Bearer ' . $access_token
            ]);

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->call('GET', '/api/vehicles/opinion', [
                'idVehicle' => "49"
            ]);

            Vehicle::truncate();

            $response->assertStatus(200);
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\People;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PeopleTest extends TestCase
{
    // use DatabaseTransactions;

    public function test_populate(): void
    {

        $credentials = [
            'email' => env('TEST_USER'),
            'password' => env('TEST_PASS')
        ];

        if (Auth::attempt($credentials)) {

            $access_token = Auth::user()->createToken('authToken')->accessToken;

            $response = $this->post('/api/people/populate', [], [    
                'Accept' => 'application/json' ,
                'Authorization' => 'Bearer ' . $access_token
            ]);
          
            People::truncate();

            $response->assertStatus(200);

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

            $response = $this->post('/api/people/populate', [], [    
                'Accept' => 'application/json' ,
                'Authorization' => 'Bearer ' . $access_token
            ]);

            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token
            ])->call('GET', '/api/people/opinion', [
                'idCharacter' => "49"
            ]);

            People::truncate();

            $response->assertStatus(200);
        }
    }
}

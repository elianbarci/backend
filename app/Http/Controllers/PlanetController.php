<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanetResource;
use App\Http\Traits\AskVaderTrait;
use App\Models\Planet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PlanetController extends Controller
{

    use AskVaderTrait;

    public function index()
    {
        //
    }

    public function getPlanet(Request $request){

        $request->validate([
            'idPlanet' => 'required|string',
        ]);

        if (Planet::count() != 0) {

            try {

                $planet = Planet::findOrFail($request->idPlanet);

            } catch (\Throwable $th) {

                return response()->json([
                    'message' => 'No existe, prueba otro ID!',
                ], 403);

            }

            return response()->json([
                'message' => 'Se ha obtenido el planeta correctamente!',
                'Vader says' => $this->askVader("Hey Darth Vader, Lord of the Sith, Commander-in-Chief of the Imperial Military, what do you think of " . $planet->name . " shall we destroy it?"),
                'data' => new PlanetResource($planet)
            ], 200);

        } else {

            return response()->json([
                'message' => 'Deberias popular primero!',
            ], 403);

        }
        
    }
    
    public function populate()
    {

        $client = new Client();
        $page = 1;
        $apiUrl = 'planets/?page=' . $page;

            Planet::truncate();

            while (true) {

                try {

                    $response = $client->request('GET', env('STARWARS_API', 'https://swapi.dev/api/') . $apiUrl, [
                        'verify' => false,
                    ]);

                    $responseBody = json_decode($response->getBody());

                    foreach ($responseBody->results as $planet) {

                        $newPlanet = Planet::insertOrIgnore([
                            'name' => $planet->name,
                            'terrain' => $planet->terrain,
                            'population' => $planet->population
                        ]);

                    }

                    $apiUrl = "planets/?page=" . $page++;
                    
                } catch (\Throwable $th) {

                    break;

                }

            }

        return response()->json([
            'message' => 'La DB ha sido cargada correctamente con los datos de los planetas!',
            'data' => PlanetResource::collection(Planet::get())
        ], 200);
    
    }
}

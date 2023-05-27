<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeopleCollection;
use App\Http\Resources\PeopleResource;
use App\Http\Traits\AskVaderTrait;
use App\Models\People;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    use AskVaderTrait;

    public function index()
    {
        //
    }

    public function getCharacter(Request $request)
    {
        
        $request->validate([
            'idCharacter' => 'required|string',
        ]);

        if (People::count() != 0) {

            try {

                $character = People::findOrFail($request->idCharacter);

            } catch (\Throwable $th) {

                return response()->json([
                    'message' => 'No existe, prueba otro ID!',
                ], 403);

            }

            return response()->json([
                'message' => 'Se ha obtenido el personaje correctamente!',
                'Vader says' => $this->askVader("Hey Darth Vader, Lord of the Sith, Commander-in-Chief of the Imperial Military, what do you think of " . $character->name . " shall we destroy he/she/it?"),
                'data' => new PeopleResource($character)
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
        $apiUrl = 'people/?page=' . $page;

        // DB::beginTransaction();

        // try {

        People::truncate();

        while (true) {

            try {


                $response = $client->request('GET', env('STARWARS_API', 'https://swapi.dev/api/') . $apiUrl, [
                    'verify' => false,
                ]);

                $responseBody = json_decode($response->getBody());

                foreach ($responseBody->results as $person) {

                    $newPerson = People::insertOrIgnore([
                        'name' => $person->name,
                        'birth_year' => $person->birth_year,
                        'gender' => $person->gender
                    ]);
                }


                $apiUrl = "people/?page=" . $page++;
            } catch (\Throwable $th) {

                break;
            }
        }


        // } catch (\Throwable $th) {

        //     DB::rollBack();

        //     return $th;
        // }

        // DB::commit();

        return response()->json([
            'message' => 'La DB ha sido cargada correctamente con los datos de los personajes!',
            'data' => PeopleResource::collection(People::get())
        ], 200);

    }

}

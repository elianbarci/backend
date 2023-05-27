<?php

namespace App\Http\Controllers;

use App\Http\Resources\PeopleCollection;
use App\Http\Resources\PeopleResource;
use App\Models\People;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{

    public function index()
    {
        //
    }

    public function getCharacter(Request $request){

        $request->validate([
            'id' => 'required|string',
        ]);



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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehicleResource;
use App\Http\Traits\AskVaderTrait;
use App\Models\Vehicle;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class VehicleController extends Controller
{

    use AskVaderTrait;

    public function index(){
        return response()->json([
            'message' => 'Estas en la seccion de vehiculos!',
        ], 200);
    }

    public function getVehicle(Request $request)
    {

        $request->validate([
            'idVehicle' => 'required|string',
        ]);

        if (Vehicle::count() != 0) {

            try {

                $vehicle = Vehicle::findOrFail($request->idVehicle);

            } catch (\Throwable $th) {

                return response()->json([
                    'message' => 'No existe, prueba otro ID!',
                ], 403);

            }

            return response()->json([
                'message' => 'Se ha obtenido el planeta correctamente!',
                'Vader says' => $this->askVader("Hey Darth Vader, Lord of the Sith, Commander-in-Chief of the Imperial Military, what do you think of " . $vehicle->name . " shall we destroy it?"),
                'data' => new VehicleResource($vehicle)
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
        $apiUrl = 'vehicles/?page=' . $page;

        Vehicle::truncate();

        while (true) {

            try {

                $response = $client->request('GET', env('STARWARS_API', 'https://swapi.dev/api/') . $apiUrl, [
                    'verify' => false,
                ]);

                $responseBody = json_decode($response->getBody());

                if(!$responseBody){
                    error_log("La respuesta esta vacia");
                    break;
                }

                foreach ($responseBody->results as $vehicle) {

                    $newVehicle = Vehicle::insertOrIgnore([
                        'name' => $vehicle->name,
                        'model' => $vehicle->model,
                        'manufacturer' => $vehicle->manufacturer,
                        'consumables' => $vehicle->consumables,
                        'crew' => $vehicle->crew
                    ]);

                }

                $apiUrl = "vehicles/?page=" . $page++;

            } catch (\Throwable $th) {

                break;
            }
        }

        return response()->json([
            'message' => 'La DB ha sido cargada correctamente con los datos de los vehiculos!',
            'data' => VehicleResource::collection(Vehicle::get())
        ], 200);

    }

}

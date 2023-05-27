<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StarWarsController extends Controller
{

    public function limiter()
    {
        //Este es un metodo publico que sirve solo y unicamente para comprobar que a 10 intentos en menos de un minuto la request es denegada a nivel IP.
        
        return response()->json([
            'message' => 'Darth Vader dice Hola',
        ], 200);
    
    }

    public function populateDatabase(Request $request){

        
        
    }

    public function getElement(Request $request){
        //Obtiene un elemento
        //Se le envia el elemento a querer obtener y la categoria del mismo (planeta)
    }








}

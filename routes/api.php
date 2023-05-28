<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\PlanetController;
use App\Http\Controllers\StarWarsController;
use App\Http\Controllers\VehicleController;
use App\Http\Resources\PeopleCollection;
use App\Http\Resources\PlanetCollection;
use App\Http\Resources\VehicleCollection;
use App\Models\People;
use App\Models\Planet;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/limiter', [StarWarsController::class, 'limiter']);

Route::group([
    'prefix' => 'auth'
], function ($router) { 
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group([
    'prefix' => 'people',
    'middleware' => ['auth:api']
], function ($router) { 


    Route::get('/all', function () {
        return new PeopleCollection(People::paginate(20));
    }); 
    
    Route::get('/opinion', [PeopleController::class, 'getCharacter']);
    
    Route::get('/populate', [PeopleController::class, 'populate']);


});

Route::group([
    'prefix' => 'planets',
    'middleware' => ['auth:api']
], function ($router) { 


    Route::get('/all', function () {
        return new PlanetCollection(Planet::paginate(20));
    }); 
    
    Route::get('/opinion', [PlanetController::class, 'getPlanet']);
    
    Route::get('/populate', [PlanetController::class, 'populate']);


});

Route::group([
    'prefix' => 'vehicles',
    'middleware' => ['auth:api']
], function ($router) { 


    Route::get('/all', function () {
        return new VehicleCollection(Vehicle::paginate(20));
    }); 
    
    Route::get('/opinion', [VehicleController::class, 'getVehicle']);
    
    Route::get('/populate', [VehicleController::class, 'populate']);


});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::namespace('Api')->group(function (){
    /**Game */
    Route::post('/test','Game\GameController@publish');
    //Game增删改查
    Route::post('/game/add','Game\GameController@publish');
    Route::get('/game/me/{uid}','Game\GameController@get_me_list');
    Route::get('/game/like/{uid}','Game\GameController@get_like_list');
    Route::get('/game/collection/{uid}','Game\GameController@get_collection_list');
});

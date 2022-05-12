<?php

use App\Models\User;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    $server = config('app.name');

    $users = User::limit(1000)
        ->inRandomOrder()
        ->get()
        ->shuffle()
        ->transform(function ($item) use ($server) {
            $item->server_name = $server;

            return $item;
        })
        ->shuffle()
        ->shuffle()
        ->take(100);

    return response()->json($users);
});

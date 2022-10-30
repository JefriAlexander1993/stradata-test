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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'namespace' => 'Api',
    'middleware' => 'api',
    'prefix' => 'v1'

], function () {

    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    // Route::post('refresh', 'AuthController@refresh');
    Route::post('/me', 'AuthController@me');
    Route::post('/checkToken', 'AuthController@checkToken');
    Route::post('/admin', 'AdminController@index');

    Route::get('/file-import-export', 'PersonPublicController@fileImportExport')->name('file-import-export');
    Route::post('/file-import', 'PersonPublicController@fileImport')->name('file-import');
    Route::post('/file-export', 'PersonPublicController@fileExport')->name('file-export');
    Route::post('/filter-person-public', 'PersonPublicController@filterPersonPublic')->name('filter-person-public');
});

<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('contacts', ContactController::class);
    Route::get('export-contacts', [ContactController::class, 'exportContacts'])->name('export.contacts');
    Route::post('import-contacts', [ContactController::class, 'importContacts'])->name('import.contacts');
    Route::post('track-klaviyo', [ContactController::class, 'trackKlaviyo'])->name('track.klaviyo');
});

require __DIR__ . '/auth.php';

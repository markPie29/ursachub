<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrsacHubController;

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

Route::get('/home', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// Route::get('/addproduct', function () {
//     return view('admin_addprod');
// });

Route::get('/admin_account', 'App\Http\Controllers\UrsacHubController@admin');

Route::get('/products_page', 'App\Http\Controllers\UrsacHubController@products_page');

Route::get('/products/{id}', [UrsacHubController::class, 'show_prodpage'])->name('show_prodpage');

Route::get('/create', [UrsacHubController::class, 'create'])->name('create'); // Updated route

Route::post('/add', [UrsacHubController::class, 'addproduct'])->name('addproduct'); // POST route for adding







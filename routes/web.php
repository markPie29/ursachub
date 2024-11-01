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



Route::get('/admin_account', 'App\Http\Controllers\UrsacHubController@admin'); //ADMIN

Route::post('/addnews', 'App\Http\Controllers\UrsacHubController@addnews'); //ADMIN

Route::get('/addnewspage', 'App\Http\Controllers\UrsacHubController@addnewspage'); //ADMIN

Route::get('/news_page', 'App\Http\Controllers\UrsacHubController@news_page'); //STUDENT
 
Route::get('/news/{id}', 'App\Http\Controllers\UrsacHubController@show_eachnewspage')->name('show_eachnewspage'); //STUDENT

Route::get('/news_admin/{id}', 'App\Http\Controllers\UrsacHubController@show_eachnewspage_admin')->name('show_eachnewspage_admin'); //ADMIN

Route::put('/news/{id}/edit', [UrsacHubController::class, 'editNews'])->name('editNews'); //ADMIN

Route::delete('/news_admin/{id}', 'App\Http\Controllers\UrsacHubController@delete_news')->name('delete_news'); //ADMIN


Route::get('/products_page', 'App\Http\Controllers\UrsacHubController@products_page'); //STUDENT

Route::get('/products/{id}', 'App\Http\Controllers\UrsacHubController@show_eachprodpage')->name('show_eachprodpage'); //STUDENT

Route::get('/products_admin/{id}', 'App\Http\Controllers\UrsacHubController@show_eachprodpage_admin')->name('show_eachprodpage_admin'); //ADMIN

Route::get('/addprodpage', 'App\Http\Controllers\UrsacHubController@addprodpage'); //ADMIN

Route::post('/addprod', 'App\Http\Controllers\UrsacHubController@addprod'); //ADMIN

Route::delete('/products_admin/{id}', 'App\Http\Controllers\UrsacHubController@delete_prod')->name('delete_prod'); //ADMIN

Route::put('/products/{id}/edit_stock/{size}', [UrsacHubController::class, 'editStock'])->name('edit_stock'); //ADMIN




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



Route::get('/admin_account', 'App\Http\Controllers\UrsacHubController@admin');



Route::post('/addnews', 'App\Http\Controllers\UrsacHubController@addnews');

Route::get('/addnewspage', 'App\Http\Controllers\UrsacHubController@addnewspage');

Route::get('/news_page', 'App\Http\Controllers\UrsacHubController@news_page');

Route::get('/news/{id}', 'App\Http\Controllers\UrsacHubController@show_eachnewspage')->name('show_eachnewspage');

Route::get('/news_admin/{id}', 'App\Http\Controllers\UrsacHubController@show_eachnewspage_admin')->name('show_eachnewspage_admin');

Route::put('/news/{id}/edit', [UrsacHubController::class, 'editNews'])->name('editNews');

Route::delete('/news_admin/{id}', 'App\Http\Controllers\UrsacHubController@delete_news')->name('delete_news');


Route::get('/products_page', 'App\Http\Controllers\UrsacHubController@products_page');

Route::get('/products/{id}', 'App\Http\Controllers\UrsacHubController@show_eachprodpage')->name('show_eachprodpage');

Route::get('/products_admin/{id}', 'App\Http\Controllers\UrsacHubController@show_eachprodpage_admin')->name('show_eachprodpage_admin');

Route::get('/addprodpage', 'App\Http\Controllers\UrsacHubController@addprodpage'); 

Route::post('/addprod', 'App\Http\Controllers\UrsacHubController@addprod');

Route::delete('/products_admin/{id}', 'App\Http\Controllers\UrsacHubController@delete_prod')->name('delete_prod');

Route::put('/products/{id}/edit_stock/{size}', [UrsacHubController::class, 'editStock'])->name('edit_stock');




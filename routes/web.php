<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrsacHubController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\AdminAuthController;

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
    return view('auth.login_student');
});

// Route::get('/home', function () {
//     return view('home');
// });

// Route::get('/login', function () {
//     return view('login');
// });

// Route::get('/register', function () {
//     return view('register');
// });



Route::prefix('admin')->group(function () {
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('account', [UrsacHubController::class, 'admin'])->name('admin.account');
        Route::post('addnews', [UrsacHubController::class, 'addnews']);
        Route::get('addnewspage', [UrsacHubController::class, 'addnewspage'])->name('addnewspage');
        Route::get('news_admin/{id}', [UrsacHubController::class, 'show_eachnewspage_admin'])->name('show_eachnewspage_admin');
        Route::put('news/{id}/edit', [UrsacHubController::class, 'editNews'])->name('editNews');
        Route::delete('news/{id}', [UrsacHubController::class, 'delete_news'])->name('delete_news');
        Route::get('products_admin/{id}', [UrsacHubController::class, 'show_eachprodpage_admin'])->name('show_eachprodpage_admin');
        Route::get('/addprodpage', [UrsacHubController::class, 'addprodpage'])->name('addprodpage');
        Route::post('addprod', [UrsacHubController::class, 'addprod']);
        Route::delete('products/{id}', [UrsacHubController::class, 'delete_prod'])->name('delete_prod');
        Route::put('products/{id}/edit_stock/{size}', [UrsacHubController::class, 'editStock'])->name('edit_stock');
    });
});

Route::prefix('student')->group(function () {
    Route::get('register', [StudentAuthController::class, 'showRegisterForm'])->name('student.register');
    Route::post('register', [StudentAuthController::class, 'register']);
    Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('login', [StudentAuthController::class, 'login']);
    
    Route::middleware('auth:student')->group(function () {
        Route::get('home', [UrsacHubController::class, 'home'])->name('student.home');
        Route::get('news', [UrsacHubController::class, 'news_page'])->name('news_page');
        Route::get('products', [UrsacHubController::class, 'products_page'])->name('products_page');
        Route::get('products/{id}', [UrsacHubController::class, 'show_eachprodpage'])->name('show_eachprodpage');
        
    });
});

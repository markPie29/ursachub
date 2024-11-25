<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrsacHubController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Hash;

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


Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

Route::prefix('admin')->group(function () {
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login']);
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('account', [UrsacHubController::class, 'admin'])->name('admin.account');
        Route::put('account/update_name', [AdminAuthController::class, 'admin_update_name'])->name('admin.update_name');
        Route::post('account/update_password', [AdminAuthController::class, 'admin_update_password'])->name('admin.update_password');
        Route::post('account/update_gcash', [AdminAuthController::class, 'admin_update_gcash'])->name('admin.update_gcash');
        Route::get('/products', [UrsacHubController::class, 'adminProducts'])->name('admin.products');
        Route::get('/news', [UrsacHubController::class, 'adminNews'])->name('admin.news');

        Route::post('addnews', [UrsacHubController::class, 'addnews'])->name('admin.addnews');
        Route::get('addnewspage', [UrsacHubController::class, 'addnewspage'])->name('addnewspage');
        Route::get('news_admin/{id}', [UrsacHubController::class, 'show_eachnewspage_admin'])->name('show_eachnewspage_admin');
        Route::put('news/{id}/edit', [UrsacHubController::class, 'editNews'])->name('editNews');
        Route::delete('news/{id}', [UrsacHubController::class, 'delete_news'])->name('delete_news');
        Route::get('products_admin/{id}', [UrsacHubController::class, 'show_eachprodpage_admin'])->name('show_eachprodpage_admin');
        Route::get('/addprodpage', [UrsacHubController::class, 'addprodpage'])->name('addprodpage');
        Route::post('addprod', [UrsacHubController::class, 'addprod'])->name('admin.addprod');
        Route::delete('products/{id}', [UrsacHubController::class, 'delete_prod'])->name('delete_prod');
        Route::put('/products/{id}/update-stocks', [UrsacHubController::class, 'updateStocks'])->name('update_stocks');
        Route::post('/product/{id}/toggle-edit-mode', [UrsacHubController::class, 'toggleEditMode'])->name('toggle_edit_mode');
        Route::put('/product/{id}/update-restrictions', [UrsacHubController::class, 'updateRestrictions'])->name('update_restrictions');
        Route::put('/products/{id}/update-price', [UrsacHubController::class, 'updatePrice'])->name('update_price');
        Route::get('/orders', [UrsacHubController::class, 'adminOrders'])->name('admin.orders');
        Route::put('/orders/{order}', [UrsacHubController::class, 'updateOrderStatus'])->name('admin.updateOrderStatus');
        Route::post('/upload-logo', [UrsacHubController::class, 'uploadLogo'])->name('upload.logo');
        Route::get('/track-orders', [UrsacHubController::class, 'trackOrders'])->name('admin.trackOrders');
        Route::get('/track-orders', [UrsacHubController::class, 'trackOrders'])->name('admin.trackOrders');
        Route::get('/finished-orders', [UrsacHubController::class, 'finishedOrders'])->name('admin.finishedOrders');

    });
});

Route::prefix('student')->group(function () {
    Route::get('register', [StudentAuthController::class, 'showRegisterForm'])->name('student.register');
    Route::post('register', [StudentAuthController::class, 'register']);
    Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('login', [StudentAuthController::class, 'login']);
    
    Route::middleware(['auth:student'])->group(function () {
        Route::get('home', [UrsacHubController::class, 'home'])->name('student.home');
        Route::get('account', [UrsacHubController::class, 'student_account'])->name('student.account');
        Route::post('account/update_password', [StudentAuthController::class, 'student_update_password'])->name('student.update_password');
        Route::get('cart', [UrsacHubController::class, 'student_cart'])->name('student.cart');
        Route::post('/cart/{productId}', [UrsacHubController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/update/{id}', [UrsacHubController::class, 'updateCartQuantity'])->name('cart.update');
        Route::post('/cart/remove/{itemId}', [UrsacHubController::class, 'removeItem'])->name('cart.remove');
        Route::post('/cart/checkout', [UrsacHubController::class, 'checkout'])->name('cart.checkout');
        Route::post('/place-order', [UrsacHubController::class, 'placeOrder'])->name('place.order');
        Route::get('/orders', [UrsacHubController::class, 'studentOrders'])->name('student.orders');
        Route::get('/search-products', [UrsacHubController::class, 'searchProducts'])->name('search_products');
        Route::get('/search-news', [UrsacHubController::class, 'searchNews'])->name('search_news');
        Route::get('news', [UrsacHubController::class, 'news_page'])->name('news_page');
        Route::get('products', [UrsacHubController::class, 'products_page'])->name('products_page');
        Route::get('products/{id}', [UrsacHubController::class, 'show_eachprodpage'])->name('show_eachprodpage');
        Route::get('news/{id}', [UrsacHubController::class, 'show_eachnewspage'])->name('show_eachnewspage');
        Route::get('orgs', [UrsacHubController::class, 'orgs_page'])->name('orgs_page');
        Route::get('orgs/{id}', [UrsacHubController::class, 'show_eachorgs'])->name('show_eachorgs');
        Route::get('/search-orgs', [UrsacHubController::class, 'searchOrgs'])->name('search_orgs');
    });
});

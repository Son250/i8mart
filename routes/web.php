<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminOrderItemController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ClientController::class, 'home']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [ClientController::class, 'home'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'admin'])->group(function () {

    //admin
    Route::get('admin', [DashboardController::class, 'show']);


    //user
    Route::get('admin/user/list', [AdminUserController::class, 'list']);
    Route::get('admin/user/add', [AdminUserController::class, 'add']);
    Route::post('admin/user/store', [AdminUserController::class, 'store']);
    Route::get('admin/user/action', [AdminUserController::class, 'action']);

    Route::get('admin/user/edit/{id}', [AdminUserController::class, 'edit'])->name('editUser');
    Route::post('admin/user/update/{id}', [AdminUserController::class, 'update'])->name('updateUser');

    Route::get('admin/user/delete/{id}', [AdminUserController::class, 'delete'])->name('deleteUser');
    Route::get('admin/user/restore/{id}', [AdminUserController::class, 'restore'])->name('restoreUser');
    Route::get('admin/user/hardDelete/{id}', [AdminUserController::class, 'hardDelete'])->name('hardDeleteUser');


    //Danh mục - Product_categories
    Route::get('admin/product/cat/list', [AdminCategoryController::class, 'list']);
    Route::post('admin/product/cat/add', [AdminCategoryController::class, 'add']);
    Route::get('admin/product/cat/delete/{id}', [AdminCategoryController::class, 'delete'])->name('deleteCat');
    Route::get('admin/product/cat/edit/{id}', [AdminCategoryController::class, 'edit'])->name('editCat');
    Route::post('admin/product/cat/update/{id}', [AdminCategoryController::class, 'update'])->name('updateCat');


    //Sản phẩm - product
    Route::get('admin/product/list', [AdminProductController::class, 'list']);
    Route::get('admin/product/add', [AdminProductController::class, 'add']);
    Route::post('admin/product/store', [AdminProductController::class, 'store']);
    Route::get('admin/product/restoreProduct/{id}', [AdminProductController::class, 'restoreProduct'])->name('restoreProduct');
    Route::get('admin/product/delete/{id}', [AdminProductController::class, 'delete'])->name('deleteProduct');
    Route::get('admin/product/hardDeleteProduct/{id}', [AdminProductController::class, 'hardDeleteProduct'])->name('hardDeleteProduct');
    Route::get('admin/product/edit/{id}', [AdminProductController::class, 'edit'])->name('editProduct');
    Route::post('admin/product/update/{id}', [AdminProductController::class, 'update'])->name('updateProduct');
    Route::get('admin/product/action', [AdminProductController::class, 'action']);

    //Đơn hàng - order 
    Route::get('admin/order/list', [AdminOrderController::class, 'list']);
    Route::get('admin/order/edit/{id}', [AdminOrderController::class, 'edit'])->name('editOrder');
    Route::get('admin/order/deleteOrder/{id}', [AdminOrderController::class, 'deleteOrder'])->name('deleteOrder');
    Route::post('admin/order/update/{id}', [AdminOrderController::class, 'update'])->name('updateOrder');

    //Phân Quyền - Permission
    // Route::get('admin/permission/list', [AdminOrderController::class, 'list']);
});

//Trở về website
Route::get('view/home', [ClientController::class, 'home'])->name('returnWebsite');

//Client
Route::get('home', [ClientController::class, 'home'])->name('home');
Route::get('detailProduct/{id}', [ClientController::class, 'detailProduct'])->name('detailProduct');
Route::get('categoryProduct/{id}', [ClientController::class, 'categoryProduct'])->name('categoryProduct');

//Thanh toán
Route::get('checkout', [ClientController::class, 'checkout'])->name('checkout');
Route::post('checkoutStore', [ClientController::class, 'checkoutStore'])->name('checkoutStore');


//cart
Route::get('cart', [ClientController::class, 'cart'])->name('cart');
Route::post('addToCart/{id}', [ClientController::class, 'addToCart'])->name('addToCart');
Route::get('deleteItemCart/{id}', [ClientController::class, 'deleteItemCart'])->name('deleteItemCart');
Route::get('deleteCart', [ClientController::class, 'deleteCart'])->name('deleteCart');
Route::post('updateCart', [ClientController::class, 'updateCart'])->name('updateCart');


//mua ngay 
Route::post('checkoutBuyNow/{id}', [ClientController::class, 'checkoutBuyNow'])->name('checkoutBuyNow');
Route::post('checkoutBuyNowStore', [ClientController::class, 'checkoutBuyNowStore'])->name('checkoutBuyNowStore');

//Đơn hàng của bạn
Route::get('orderStatus', [ClientController::class, 'orderStatus'])->name('orderStatus');
Route::post('cancelOrder/{id}', [ClientController::class, 'cancelOrder'])->name('cancelOrder');

//Thanh toán online
Route::post('vnpay_payment', [PaymentController::class, 'vnpay_payment'])->name('vnpay_payment');

require __DIR__ . '/auth.php';

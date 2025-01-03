<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Checkout;
use App\Livewire\DistributorFormPage;
use App\Livewire\InfoBisnis;
use App\Livewire\OrderPage;
use App\Livewire\OrderDetail;
use App\Livewire\PaymentConfirmationPage;
use App\Livewire\ProductDetail;
use App\Livewire\Profile;
use App\Livewire\RegisterRole;
use App\Livewire\Reward;
use App\Livewire\RewardPage;
use App\Livewire\ShoppingCart;
use App\Livewire\StoreShow;
use Illuminate\Support\Facades\Route;

Route::get('/', StoreShow::class)->name('home');
Route::get('/product/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/reward', RewardPage::class)->name('reward');
Route::get('/info-bisnis', InfoBisnis::class)->name('info-bisnis');


Route::middleware('guest')->group(function (){
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function (){
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/shopping-cart', ShoppingCart::class)->name('shopping-cart');
    Route::get('/orders', OrderPage::class)->name('orders');
    Route::get('/order-detail/{orderNumber}', OrderDetail::class)->name('order-detail');
    Route::get('/payment-confirmation/{orderNumber}', PaymentConfirmationPage::class)->name('payment-confirmation');
});

//setelah register berhasil user harus verified email sebelum cekout
Route::middleware('auth', 'verified')->group(function () {
    // Route::get('/checkout', Checkout::class)->name('checkout');
});

require __DIR__.'/auth.php';

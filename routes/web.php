
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\DonationController;

use App\Http\Controllers\KeyController;

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

//Checkout Payment Page
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/detail', [CheckoutController::class, 'processdetail'])->name('checkout.detail');

//Payment Process: Request
Route::post('/checkout/payment-process', [PaymentController::class, 'processPayment'])->name('payment.process');

//Payment Process: Response 
Route::get('/checkout/payment-response', [PaymentController::class, 'responsePayment'])->name('payment.response');

//Payment Process: Result Page
Route::get('/checkout/payment/result/{orderId}/{transactionId}', [PaymentController::class, 'showResultPage'])->name('payment.result');

require __DIR__.'/auth.php';

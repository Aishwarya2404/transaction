<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\TransactionController::class, 'index'])->name('home');
Route::get('transactions', [TransactionController::class, 'getTransactions'])->name('transactions.index');
Route::post('/transactions/credit', [TransactionController::class, 'credit'])->name('transactions.credit');
Route::post('/transactions/debit', [TransactionController::class, 'debit'])->name('transactions.debit');
Route::post('/transactions/close', [TransactionController::class, 'close'])->name('transactions.close');
Route::post('/transactions/process-interest', [TransactionController::class, 'processInterest'])->name('transactions.process-interest');



// Accounts
Route::get('/accounts', [App\Http\Controllers\AccountController::class, 'index'])->name('accounts.index');
Route::get('/accounts/create', [App\Http\Controllers\AccountController::class, 'create'])->name('accounts.create');
Route::post('/accounts', [App\Http\Controllers\AccountController::class, 'store'])->name('accounts.store');
Route::get('/accounts/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('accounts.show');
Route::get('/accounts/{account}/edit', [App\Http\Controllers\AccountController::class, 'edit'])->name('accounts.edit');
Route::put('/accounts/{account}', [App\Http\Controllers\AccountController::class, 'update'])->name('accounts.update');
Route::delete('/accounts/{account}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('accounts.destroy');


<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliverymanController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
Route::get('/banks/{bank}', [BankController::class, 'show'])->name('banks.show');
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
Route::put('/trips/{trip}', [TripController::class, 'update'])->name('trips.update');
Route::put('/trips/{trip}/delivery-result', [TripController::class, 'updateDeliveryResult'])->name('trips.delivery-result.update');
Route::delete('/trips/{trip}', [TripController::class, 'destroy'])->name('trips.destroy');
Route::post('/trips/{trip}/collections', [TripController::class, 'storeCollection'])->name('trips.collections.store');
Route::put('/trips/{trip}/collections/{collection}', [TripController::class, 'updateCollection'])->name('trips.collections.update');
Route::post('/trips/{trip}/expenses', [TripController::class, 'storeExpense'])->name('trips.expenses.store');
Route::put('/trips/{trip}/expenses/{expense}', [TripController::class, 'updateExpense'])->name('trips.expenses.update');
Route::post('/trips/{trip}/close', [TripController::class, 'close'])->name('trips.close');

Route::get('/deliverymen', [DeliverymanController::class, 'index'])->name('deliverymen.index');
Route::get('/deliverymen/{id}', [DeliverymanController::class, 'show'])->name('deliverymen.show');

Route::get('/markets', [MarketController::class, 'index'])->name('markets.index');
Route::get('/markets/{id}', [MarketController::class, 'show'])->name('markets.show');

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
Route::get('/stock/{id}', [StockController::class, 'show'])->name('stock.show');
Route::put('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
Route::get('/returns/create', [ReturnController::class, 'create'])->name('returns.create');
Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
Route::get('/returns/{return}', [ReturnController::class, 'show'])->name('returns.show');
Route::get('/returns/{return}/edit', [ReturnController::class, 'edit'])->name('returns.edit');
Route::put('/returns/{return}', [ReturnController::class, 'update'])->name('returns.update');
Route::delete('/returns/{return}', [ReturnController::class, 'destroy'])->name('returns.destroy');
Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/settlements', [SettlementController::class, 'index'])->name('settlements.index');
Route::get('/settlements/create', [SettlementController::class, 'create'])->name('settlements.create');
Route::get('/settlements/{settlement}', [SettlementController::class, 'show'])->name('settlements.show');
Route::get('/ledgers', [LedgerController::class, 'index'])->name('ledgers.index');
Route::get('/ledgers/create', [LedgerController::class, 'create'])->name('ledgers.create');
Route::post('/ledgers', [LedgerController::class, 'store'])->name('ledgers.store');
Route::get('/ledgers/{id}', [LedgerController::class, 'show'])->name('ledgers.show');
Route::get('/ledgers/{id}/edit', [LedgerController::class, 'edit'])->name('ledgers.edit');
Route::put('/ledgers/{id}', [LedgerController::class, 'update'])->name('ledgers.update');
Route::delete('/ledgers/{id}', [LedgerController::class, 'destroy'])->name('ledgers.destroy');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/trips', [ReportController::class, 'trips'])->name('reports.trips');
Route::get('/reports/deliverymen', [ReportController::class, 'deliverymen'])->name('reports.deliverymen');
Route::get('/reports/financial-summary', [ReportController::class, 'financialSummary'])->name('reports.financial-summary');
Route::get('/reports/markets', [ReportController::class, 'markets'])->name('reports.markets');
Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
Route::get('/reports/sku-movement', [ReportController::class, 'skuMovement'])->name('reports.sku-movement');
Route::get('/reports/audit-trail', [ReportController::class, 'auditTrail'])->name('reports.audit-trail');

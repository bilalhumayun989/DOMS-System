<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliverymanController;
use App\Http\Controllers\DemoController;
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

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

// ─── Demo Mode ────────────────────────────────────────────────────────────────
Route::get('/demo', [DemoController::class, 'enter'])->name('demo.enter');
Route::post('/demo/exit', [DemoController::class, 'exit'])->name('demo.exit');

Route::prefix('demo')->name('demo.')->group(function () {
    Route::get('/dashboard', [DemoController::class, 'dashboard'])->name('dashboard');

    Route::get('/trips', [DemoController::class, 'trips'])->name('trips');
    Route::post('/trips', [DemoController::class, 'storeTrip'])->name('trips.store');
    Route::put('/trips/{id}', [DemoController::class, 'updateTrip'])->name('trips.update');
    Route::delete('/trips/{id}', [DemoController::class, 'destroyTrip'])->name('trips.destroy');
    Route::get('/trips/{id}', [DemoController::class, 'tripShow'])->name('trips.show');

    Route::get('/deliverymen', [DemoController::class, 'deliverymen'])->name('deliverymen');
    Route::post('/deliverymen', [DemoController::class, 'storeDeliveryman'])->name('deliverymen.store');
    Route::put('/deliverymen/{id}', [DemoController::class, 'updateDeliveryman'])->name('deliverymen.update');
    Route::delete('/deliverymen/{id}', [DemoController::class, 'destroyDeliveryman'])->name('deliverymen.destroy');

    Route::get('/markets', [DemoController::class, 'markets'])->name('markets');
    Route::post('/markets', [DemoController::class, 'storeMarket'])->name('markets.store');
    Route::put('/markets/{id}', [DemoController::class, 'updateMarket'])->name('markets.update');
    Route::delete('/markets/{id}', [DemoController::class, 'destroyMarket'])->name('markets.destroy');

    Route::get('/banks', [DemoController::class, 'banks'])->name('banks');
    Route::post('/banks', [DemoController::class, 'storeBank'])->name('banks.store');
    Route::put('/banks/{id}', [DemoController::class, 'updateBank'])->name('banks.update');
    Route::delete('/banks/{id}', [DemoController::class, 'destroyBank'])->name('banks.destroy');

    Route::get('/invoices', [DemoController::class, 'invoices'])->name('invoices');
    Route::post('/invoices', [DemoController::class, 'storeInvoice'])->name('invoices.store');
    Route::put('/invoices/{id}', [DemoController::class, 'updateInvoice'])->name('invoices.update');
    Route::delete('/invoices/{id}', [DemoController::class, 'destroyInvoice'])->name('invoices.destroy');

    Route::get('/expenses', [DemoController::class, 'expenses'])->name('expenses');
    Route::post('/expenses', [DemoController::class, 'storeExpense'])->name('expenses.store');
    Route::put('/expenses/{id}', [DemoController::class, 'updateExpense'])->name('expenses.update');
    Route::delete('/expenses/{id}', [DemoController::class, 'destroyExpense'])->name('expenses.destroy');

    Route::get('/stock', [DemoController::class, 'stock'])->name('stock');
    Route::post('/stock', [DemoController::class, 'storeStock'])->name('stock.store');
    Route::put('/stock/{id}', [DemoController::class, 'updateStock'])->name('stock.update');
    Route::delete('/stock/{id}', [DemoController::class, 'destroyStock'])->name('stock.destroy');

    Route::get('/returns', [DemoController::class, 'returns'])->name('returns');
    Route::post('/returns', [DemoController::class, 'storeReturn'])->name('returns.store');
    Route::put('/returns/{id}', [DemoController::class, 'updateReturn'])->name('returns.update');
    Route::delete('/returns/{id}', [DemoController::class, 'destroyReturn'])->name('returns.destroy');

    Route::get('/settlements', [DemoController::class, 'settlements'])->name('settlements');
    Route::post('/settlements', [DemoController::class, 'storeSettlement'])->name('settlements.store');
    Route::put('/settlements/{id}', [DemoController::class, 'updateSettlement'])->name('settlements.update');
    Route::delete('/settlements/{id}', [DemoController::class, 'destroySettlement'])->name('settlements.destroy');

    Route::get('/collections', [DemoController::class, 'collections'])->name('collections');
    Route::post('/collections', [DemoController::class, 'storeCollection'])->name('collections.store');
    Route::put('/collections/{id}', [DemoController::class, 'updateCollection'])->name('collections.update');
    Route::delete('/collections/{id}', [DemoController::class, 'destroyCollection'])->name('collections.destroy');

    Route::get('/ledgers', [DemoController::class, 'ledgers'])->name('ledgers');
    Route::post('/ledgers', [DemoController::class, 'storeLedger'])->name('ledgers.store');
    Route::put('/ledgers/{id}', [DemoController::class, 'updateLedger'])->name('ledgers.update');
    Route::delete('/ledgers/{id}', [DemoController::class, 'destroyLedger'])->name('ledgers.destroy');

    Route::get('/reports', [DemoController::class, 'reports'])->name('reports');
});

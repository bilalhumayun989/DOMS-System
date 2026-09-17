<?php

namespace App\Providers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Deliveryman;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\Market;
use App\Models\ReturnClaim;
use App\Models\StockItem;
use App\Models\Trip;
use App\Models\TripCollection;
use App\Models\TripExpense;
use App\Models\TripSettlement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ([BankAccount::class, BankTransaction::class, Market::class,
            Deliveryman::class, Invoice::class, StockItem::class,
            Expense::class, ReturnClaim::class, Ledger::class,
            Trip::class, TripCollection::class, TripExpense::class, TripSettlement::class] as $modelClass) {
            foreach (['created', 'updated', 'deleted'] as $event) {
                $modelClass::{$event}(function (Model $record) use ($event): void {
                    DB::table('audit_logs')->insert([
                        'user' => auth()->user()?->name ?? (app()->runningInConsole() ? 'System' : 'Operator'),
                        'action' => ucfirst($event), 'entity' => class_basename($record).' #'.$record->getKey(),
                        'details' => $event === 'updated' ? 'Changed fields: '.implode(', ', array_keys($record->getChanges())) : ucfirst($event).' record.',
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                });
            }
        }
        StockItem::saved(function (StockItem $item): void {
            if ($item->wasRecentlyCreated || $item->wasChanged('current_stock')) {
                DB::table('stock_movements')->insert([
                    'stock_item_id' => $item->id, 'sku' => $item->sku_code, 'product' => $item->product_name,
                    'quantity_change' => $item->current_stock - ($item->wasRecentlyCreated ? 0 : (int) $item->getOriginal('current_stock')),
                    'balance_after' => $item->current_stock, 'type' => $item->wasRecentlyCreated ? 'Opening' : 'Adjustment',
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        });
    }
}

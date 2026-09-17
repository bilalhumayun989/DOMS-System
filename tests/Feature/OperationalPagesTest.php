<?php

namespace Tests\Feature;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Deliveryman;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\Market;
use App\Models\StockItem;
use App\Models\Trip;
use Database\Seeders\OperationalDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class OperationalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_pages_and_record_details_render_and_seeding_is_repeatable(): void
    {
        $this->seed(OperationalDataSeeder::class);
        $this->seed(OperationalDataSeeder::class);
        foreach (['bank_accounts', 'bank_transactions', 'markets', 'deliverymen', 'invoices', 'stock_items', 'return_claims', 'expenses', 'trip_collections', 'ledgers'] as $table) {
            $this->assertDatabaseCount($table, 12);
        }
        $this->assertDatabaseCount('trip_settlements', 10);
        foreach (['/', '/banks', '/expenses', '/expenses/create', '/deliverymen', '/markets', '/invoices', '/stock', '/returns', '/returns/create', '/collections', '/settlements', '/settlements/create', '/ledgers', '/ledgers/create', '/trips', '/reports', '/reports/trips', '/reports/deliverymen', '/reports/markets', '/reports/stock', '/reports/financial-summary', '/reports/sku-movement', '/reports/audit-trail'] as $url) {
            $this->get($url)->assertOk();
        }
        foreach (['banks', 'expenses', 'deliverymen', 'markets', 'invoices', 'stock', 'returns', 'settlements', 'ledgers'] as $page) {
            $this->get('/'.$page.'/1')->assertOk();
        }
        $this->get('/returns/1/edit')->assertOk();
        $this->get('/ledgers/1/edit')->assertOk();
    }

    public function test_bank_transactions_persist_and_recalculate_account_balances(): void
    {
        $account = BankAccount::factory()->create(['opening' => 1000]);
        $other = BankAccount::factory()->create(['opening' => 2000]);
        $data = ['bank_id' => $account->id, 'date' => '2026-09-02', 'type' => 'Deposit / Credit', 'category' => 'Retail Collection', 'reference' => 'DEP-TEST', 'amount' => 250];
        $this->post(route('banks.transactions.store'), $data)->assertSessionHasNoErrors()->assertRedirect();
        $transaction = BankTransaction::where('reference', 'DEP-TEST')->firstOrFail();
        $this->get(route('banks.show', $account))->assertSee('PKR 1,250.00');
        $this->put(route('banks.transactions.update', $transaction), [...$data, 'bank_id' => $other->id, 'type' => 'Withdrawal / Debit', 'amount' => 100])->assertSessionHasNoErrors()->assertRedirect();
        $this->get(route('banks.show', $account))->assertSee('PKR 1,000.00');
        $this->get(route('banks.show', $other))->assertSee('PKR 1,900.00');
        $this->delete(route('banks.transactions.destroy', $transaction))->assertRedirect();
        $this->get(route('banks.show', $other))->assertSee('PKR 2,000.00');
    }

    public function test_bank_transaction_validation_prevents_invalid_records(): void
    {
        $account = BankAccount::factory()->create();
        $this->post(route('banks.transactions.store'), ['bank_id' => $account->id, 'date' => '2026-09-02', 'type' => 'Invalid', 'amount' => -1])->assertSessionHasErrors(['type', 'amount', 'reference']);
        $this->assertDatabaseCount('bank_transactions', 0);
    }

    public function test_stock_create_edit_delete_persist(): void
    {
        $data = ['sku_code' => 'TEST-001', 'product_name' => 'Test Item', 'category' => 'Beverages', 'current_stock' => 12, 'reorder_point' => 5];
        $this->post(route('stock.store'), $data)->assertSessionHasNoErrors()->assertRedirect();
        $item = StockItem::where('sku_code', 'TEST-001')->firstOrFail();
        $this->put(route('stock.update', $item->id), [...$data, 'current_stock' => 3])->assertRedirect();
        $this->get(route('stock.show', $item->id))->assertSee('Low Stock');
        $this->delete(route('stock.destroy', $item->id))->assertRedirect();
        $this->assertDatabaseMissing('stock_items', ['id' => $item->id]);
    }

    public function test_collection_and_expense_feed_settlement_and_closed_trip_is_locked(): void
    {
        $trip = Trip::factory()->create(['expected_cash' => 1000]);
        $this->post(route('collections.store'), ['trip_display' => $trip->trip_number, 'date' => '2026-09-01', 'customer' => 'Test Store', 'invoice_number' => 'INV-TEST', 'amount' => 900, 'method' => 'Cash'])->assertSessionHasNoErrors()->assertRedirect();
        $this->post(route('expenses.store'), ['date' => '2026-09-01', 'category' => 'Fuel', 'amount' => 100, 'source' => 'Cash in Hand', 'trip_id' => $trip->id])->assertSessionHasNoErrors()->assertRedirect();
        $this->post(route('settlements.store'), ['trip_id' => $trip->id])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('CLOSED', $trip->fresh()->status);
        $this->assertDatabaseHas('trip_settlements', ['trip_id' => $trip->id, 'difference_amount' => 0]);
        $this->delete(route('collections.destroy', $trip->collections()->first()))->assertStatus(422);
    }

    public function test_market_deliveryman_and_invoice_forms_persist_changes(): void
    {
        $this->seed(OperationalDataSeeder::class);
        $marketData = ['name' => 'New Market', 'area' => 'Karachi', 'outstanding_balance' => 0];
        $this->post(route('markets.store'), $marketData)->assertSessionHasNoErrors()->assertRedirect();
        $market = Market::where('name', 'New Market')->firstOrFail();
        $this->put(route('markets.update', $market->id), [...$marketData, 'name' => 'Updated Market'])->assertSessionHasNoErrors();
        $this->assertSame('Updated Market', $market->fresh()->name);
        $driverData = ['name' => 'New Driver', 'employee_id' => 'EMP-NEW', 'phone' => '0300-1234567', 'joined_at' => '2026-09-01'];
        $this->post(route('deliverymen.store'), $driverData)->assertSessionHasNoErrors()->assertRedirect();
        $driver = Deliveryman::where('employee_id', 'EMP-NEW')->firstOrFail();
        $this->assertSame([], $driver->assigned_areas);
        $this->put(route('deliverymen.update', $driver->id), [...$driverData, 'assigned_areas' => ['Clifton']])->assertSessionHasNoErrors();
        $this->assertSame(['Clifton'], $driver->fresh()->assigned_areas);
        $trip = Trip::factory()->create();
        $invoiceData = ['invoice_number' => 'INV-NEW', 'customer' => 'New Store', 'market_id' => $market->id, 'trip_id_display' => $trip->trip_number, 'date' => '2026-09-01', 'total_value' => 2500.50, 'status' => 'NOT DELIVERED'];
        $this->post(route('invoices.store'), $invoiceData)->assertSessionHasNoErrors()->assertRedirect();
        $invoice = Invoice::where('invoice_number', 'INV-NEW')->firstOrFail();
        $this->put(route('invoices.update', $invoice->id), [...$invoiceData, 'status' => 'DELIVERED'])->assertSessionHasNoErrors();
        $this->assertSame('DELIVERED', $invoice->fresh()->status);
        $this->delete(route('markets.destroy', $market->id))->assertStatus(422);
        $this->delete(route('invoices.destroy', $invoice->id))->assertRedirect();
        $this->delete(route('markets.destroy', $market->id))->assertRedirect();
        $this->delete(route('deliverymen.destroy', $driver->id))->assertRedirect();
        $this->assertDatabaseMissing('markets', ['id' => $market->id]);
        $this->assertDatabaseMissing('deliverymen', ['id' => $driver->id]);
    }

    public function test_ledger_edits_and_deletes_recalculate_later_balances(): void
    {
        $data = ['ledger_group' => 'Supplier', 'entity_name' => 'EBM - English Biscuit Manufacturers', 'entry_date' => '2026-09-01', 'voucher_reference' => 'TEST-DEBIT', 'transaction_category' => 'Primary Stock Lifting', 'entry_type' => 'Debit', 'amount' => 1000];
        $this->post(route('ledgers.store'), $data)->assertSessionHasNoErrors()->assertRedirect();
        $debit = Ledger::where('voucher_reference', 'TEST-DEBIT')->firstOrFail();
        $this->post(route('ledgers.store'), [...$data, 'voucher_reference' => 'TEST-CREDIT', 'entry_type' => 'Credit', 'amount' => 250])->assertSessionHasNoErrors();
        $credit = Ledger::where('voucher_reference', 'TEST-CREDIT')->firstOrFail();
        $this->assertEquals(750, $credit->running_balance);
        $this->put(route('ledgers.update', $debit->id), [...$data, 'amount' => 2000])->assertSessionHasNoErrors();
        $this->assertEquals(1750, $credit->fresh()->running_balance);
        $this->delete(route('ledgers.destroy', $debit->id))->assertRedirect();
        $this->assertEquals(-250, $credit->fresh()->running_balance);
        $this->assertDatabaseHas('audit_logs', ['action' => 'Deleted', 'entity' => 'Ledger #'.$debit->id]);
    }

    public function test_stock_history_matches_saved_adjustment(): void
    {
        $item = StockItem::factory()->create(['current_stock' => 50]);
        $this->put(route('stock.update', $item->id), [...$item->toArray(), 'current_stock' => 30])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('stock_movements', ['stock_item_id' => $item->id, 'quantity_change' => -20, 'balance_after' => 30]);
    }

    public function test_demo_routes_remain_isolated_from_live_models(): void
    {
        $this->seed(OperationalDataSeeder::class);
        foreach (Route::getRoutes() as $route) {
            if (str_starts_with($route->getName() ?? '', 'demo.')) {
                $this->assertStringContainsString('DemoController@', $route->getActionName());
            }
        }
        $this->get(route('demo.enter'))->assertRedirect();
        foreach (['dashboard', 'trips', 'banks', 'deliverymen', 'markets', 'invoices', 'expenses', 'stock', 'returns', 'settlements', 'collections', 'ledgers', 'reports'] as $page) {
            $this->get(route('demo.'.$page))->assertOk();
        }
        $this->assertDatabaseCount('bank_accounts', 12);
        $this->assertDatabaseCount('markets', 12);
    }
}

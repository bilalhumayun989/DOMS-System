<?php

namespace Database\Seeders;

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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $marketNames = ['Gulshan-e-Iqbal', 'North Nazimabad', 'Liaquatabad', 'Orangi Town', 'Korangi Industrial', 'SITE Area', 'Saddar', 'Clifton', 'DHA', 'Malir', 'Shah Faisal Colony', 'Gulistan-e-Johar'];
            $driverNames = ['Ahmed Khan', 'Bilal Raza', 'Usman Tariq', 'Zubair Malik', 'Kashif Hussain', 'Faisal Ahmed', 'Imran Ali', 'Salman Rafiq', 'Naveed Aslam', 'Farhan Iqbal', 'Adnan Shah', 'Saad Hassan'];
            $banks = ['HBL', 'UBL', 'MCB', 'Meezan Bank', 'Allied Bank', 'Bank Alfalah', 'Askari Bank', 'Bank Al Habib', 'Faysal Bank', 'JS Bank', 'Standard Chartered', 'Cash in Hand'];
            for ($i = 1; $i <= 12; $i++) {
                $date = now()->startOfMonth()->addDays($i - 1)->toDateString();
                $market = Market::firstOrCreate(['name' => $marketNames[$i - 1]], [
                    'area' => ['East Karachi', 'Central Karachi', 'South Karachi'][($i - 1) % 3],
                    'contact' => $driverNames[$i - 1], 'phone' => '021-3456'.str_pad((string) $i, 4, '0', STR_PAD_LEFT), 'outstanding_balance' => 0,
                ]);
                $driver = Deliveryman::firstOrCreate(['employee_id' => sprintf('EMP-%03d', $i)], [
                    'name' => $driverNames[$i - 1], 'phone' => sprintf('0300-123%04d', $i), 'vehicle' => sprintf('KHI-%04d', 1200 + $i),
                    'joined_at' => '2026-01-01', 'assigned_areas' => [$market->name],
                ]);
                $trip = Trip::firstOrCreate(['trip_number' => sprintf('TR-SAMPLE-%03d', $i)], [
                    'trip_date' => $date, 'deliveryman_id' => $driver->id, 'deliveryman_name' => $driver->name,
                    'vehicle' => $driver->vehicle, 'market_area' => $market->name, 'source_dlf' => sprintf('DLF-SAMPLE-%03d', $i),
                    'status' => $i <= 10 ? 'CLOSED' : 'SETTLEMENT PENDING', 'load_value' => 30000 + $i * 1000,
                    'expected_cash' => 30000 + $i * 1000, 'closed_at' => $i <= 10 ? $date.' 18:00:00' : null,
                ]);
                $invoice = Invoice::firstOrCreate(['invoice_number' => sprintf('INV-SAMPLE-%03d', $i)], [
                    'customer' => ['Al-Noor General Store', 'City Mart', 'Hassan Brothers', 'Metro Supplies'][($i - 1) % 4].' - '.$market->name,
                    'market_id' => $market->id, 'trip_id' => $trip->id, 'date' => $date, 'total_value' => $trip->load_value, 'status' => 'DELIVERED',
                ]);
                $collection = TripCollection::firstOrCreate(['collection_ref' => sprintf('COL-SAMPLE-%03d', $i)], [
                    'trip_id' => $trip->id, 'customer' => $invoice->customer, 'invoice_number' => $invoice->invoice_number,
                    'amount' => 29000 + $i * 1000, 'method' => 'Cash', 'collected_at' => $date.' 16:00:00',
                ]);
                $expense = Expense::firstOrCreate(['expense_id' => sprintf('EXP-SAMPLE-%03d', $i)], [
                    'date' => $date, 'category' => ['Fuel', 'Driver Allowance', 'Loading'][($i - 1) % 3], 'source' => 'Cash in Hand',
                    'driver' => $driver->name, 'route' => $driver->vehicle, 'amount' => 1000, 'voucher' => sprintf('VCH-SAMPLE-%03d', $i),
                    'status' => 'Approved', 'approved_by' => 'Admin', 'payment_source' => 'Cash', 'receipt' => sprintf('VCH-SAMPLE-%03d', $i),
                    'market' => $market->name, 'created_by' => 'Admin', 'notes' => 'Sample delivery route expense.', 'trip_id' => $trip->id,
                ]);
                TripExpense::firstOrCreate(['expense_ref' => $expense->expense_id], [
                    'trip_id' => $trip->id, 'category' => $expense->category, 'amount' => $expense->amount,
                    'description' => $expense->notes, 'expense_date' => $date,
                ]);
                if ($i <= 10) {
                    TripSettlement::firstOrCreate(['trip_id' => $trip->id], [
                        'expected_cash' => $trip->expected_cash, 'collected_amount' => $collection->amount,
                        'expense_amount' => $expense->amount, 'difference_amount' => 0, 'notes' => 'Sample reconciled trip.', 'settled_at' => $date.' 18:00:00',
                    ]);
                }
                $bank = BankAccount::firstOrCreate(['account' => sprintf('SAMPLE-ACCOUNT-%03d', $i)], [
                    'bank' => $banks[$i - 1], 'opening' => 100000 + $i * 25000, 'opening_date' => now()->startOfMonth()->toDateString(),
                    'type' => $i === 12 ? 'Cash Account' : 'Business Current', 'branch' => $market->name, 'status' => 'Active',
                ]);
                BankTransaction::firstOrCreate(['reference' => sprintf('TXN-SAMPLE-%03d', $i)], [
                    'bank_id' => $bank->id, 'date' => $date, 'type' => $i % 3 === 0 ? 'Withdrawal / Debit' : 'Deposit / Credit',
                    'category' => $i % 3 === 0 ? 'Expenses' : 'Retail Collection', 'description' => 'Sample account activity.', 'amount' => 5000 + $i * 100,
                ]);
                $skuData = $this->stockItemFixtures()[$i - 1];
                unset($skuData['id']);
                $stock = StockItem::firstOrCreate(['sku_code' => $skuData['sku_code']], $skuData);
                if (! DB::table('stock_movements')->where('stock_item_id', $stock->id)->exists()) {
                    DB::table('stock_movements')->insert(['stock_item_id' => $stock->id, 'sku' => $stock->sku_code,
                        'product' => $stock->product_name, 'quantity_change' => $stock->current_stock, 'balance_after' => $stock->current_stock,
                        'type' => 'Opening', 'created_at' => now(), 'updated_at' => now()]);
                }
                DB::table('audit_logs')->updateOrInsert(['action' => 'Sample imported', 'entity' => 'Sample set #'.$i],
                    ['user' => 'System', 'details' => 'Sample operational records imported.', 'created_at' => now(), 'updated_at' => now()]);
                $claim = $this->returnClaimFixtures()[($i - 1) % 3];
                unset($claim['id']);
                $claim = [...$claim, 'return_ref' => sprintf('RET-SAMPLE-%03d', $i), 'date' => $date, 'trip_id' => $trip->id,
                    'trip_display' => $trip->trip_number, 'invoice_ref' => $invoice->invoice_number, 'shop' => $invoice->customer,
                    'market' => $market->name, 'deliveryman' => $driver->name];
                ReturnClaim::firstOrCreate(['return_ref' => $claim['return_ref']], $claim);
                Ledger::firstOrCreate(['voucher_reference' => sprintf('LED-SAMPLE-%03d', $i)], [
                    'ledger_group' => $i <= 8 ? 'Supplier' : 'Driver',
                    'entity_name' => $i <= 8 ? ($i % 2 ? 'EBM - English Biscuit Manufacturers' : 'CFL - Coronet Foods Limited') : $driver->name,
                    'entry_date' => $date, 'transaction_category' => $i <= 8 ? 'Primary Stock Lifting' : 'Driver Cash Shortage',
                    'entry_type' => 'Debit', 'amount' => 2000, 'previous_balance' => $i <= 8 ? (int) floor(($i - 1) / 2) * 2000 : 0,
                    'running_balance' => $i <= 8 ? (int) ceil($i / 2) * 2000 : 2000,
                    'payment_method' => 'Cash Drawer', 'linked_invoice_trip' => $trip->trip_number, 'remarks' => 'Sample ledger entry.',
                ]);
            }
        });
    }

    private function stockItemFixtures(): array
    {
        return [
            ['id' => 1, 'sku_code' => 'BEV-001', 'product_name' => 'Pepsi 1.5L', 'category' => 'Beverages', 'current_stock' => 480, 'reorder_point' => 100],
            ['id' => 2, 'sku_code' => 'BEV-002', 'product_name' => 'Coca-Cola 1.5L', 'category' => 'Beverages', 'current_stock' => 360, 'reorder_point' => 100],
            ['id' => 3, 'sku_code' => 'BEV-003', 'product_name' => 'Nestle Water 1.5L', 'category' => 'Beverages', 'current_stock' => 600, 'reorder_point' => 200],
            ['id' => 4, 'sku_code' => 'BEV-004', 'product_name' => 'Sprite 1.5L', 'category' => 'Beverages', 'current_stock' => 85, 'reorder_point' => 100],
            ['id' => 5, 'sku_code' => 'BEV-005', 'product_name' => 'Fanta 1.5L', 'category' => 'Beverages', 'current_stock' => 72, 'reorder_point' => 100],
            ['id' => 6, 'sku_code' => 'BEV-006', 'product_name' => 'Mango Juice 1L', 'category' => 'Beverages', 'current_stock' => 0, 'reorder_point' => 50],
            ['id' => 7, 'sku_code' => 'SNK-001', 'product_name' => 'Lays Classic 100g', 'category' => 'Snacks', 'current_stock' => 240, 'reorder_point' => 100],
            ['id' => 8, 'sku_code' => 'SNK-002', 'product_name' => 'Kurkure 70g', 'category' => 'Snacks', 'current_stock' => 180, 'reorder_point' => 100],
            ['id' => 9, 'sku_code' => 'SNK-003', 'product_name' => 'Biscuits Marie 150g', 'category' => 'Snacks', 'current_stock' => 320, 'reorder_point' => 150],
            ['id' => 10, 'sku_code' => 'SNK-004', 'product_name' => 'Nimko Mix 200g', 'category' => 'Snacks', 'current_stock' => 140, 'reorder_point' => 100],
            ['id' => 11, 'sku_code' => 'SNK-005', 'product_name' => 'Oreo Cookies 137g', 'category' => 'Snacks', 'current_stock' => 96, 'reorder_point' => 100],
            ['id' => 12, 'sku_code' => 'SNK-006', 'product_name' => 'Pringles Original 165g', 'category' => 'Snacks', 'current_stock' => 60, 'reorder_point' => 80],
            ['id' => 13, 'sku_code' => 'SNK-007', 'product_name' => 'Chocolate Bar 50g', 'category' => 'Snacks', 'current_stock' => 200, 'reorder_point' => 100],
            ['id' => 14, 'sku_code' => 'HH-001', 'product_name' => 'Surf Excel 500g', 'category' => 'Household', 'current_stock' => 150, 'reorder_point' => 80],
            ['id' => 15, 'sku_code' => 'HH-002', 'product_name' => 'Ariel Detergent 1kg', 'category' => 'Household', 'current_stock' => 90, 'reorder_point' => 50],
            ['id' => 16, 'sku_code' => 'HH-003', 'product_name' => 'Lifebuoy Soap 125g', 'category' => 'Household', 'current_stock' => 480, 'reorder_point' => 200],
            ['id' => 17, 'sku_code' => 'HH-004', 'product_name' => 'Colgate Toothpaste 150ml', 'category' => 'Household', 'current_stock' => 220, 'reorder_point' => 100],
            ['id' => 18, 'sku_code' => 'HH-005', 'product_name' => 'Head & Shoulders 200ml', 'category' => 'Household', 'current_stock' => 75, 'reorder_point' => 80],
            ['id' => 19, 'sku_code' => 'HH-006', 'product_name' => 'Dettol 500ml', 'category' => 'Household', 'current_stock' => 110, 'reorder_point' => 60],
            ['id' => 20, 'sku_code' => 'HH-007', 'product_name' => 'Harpic Toilet Cleaner', 'category' => 'Household', 'current_stock' => 65, 'reorder_point' => 50],
        ];
    }

    private function returnClaimFixtures(): array
    {
        return [
            ['id' => 1, 'return_ref' => 'RET-2026-09-001', 'date' => '03-Sep-2026', 'trip_id' => 1, 'trip_display' => 'TR-2026-09-02-001', 'invoice_ref' => 'INV-8892', 'shop' => 'Al-Noor General Store', 'market' => 'Gulshan-e-Iqbal', 'distributor' => 'AAA Traders', 'deliveryman' => 'Ahmed Khan', 'return_type' => 'Expiry Claim', 'units' => '5 Cartons', 'value' => 14500, 'status' => 'Pending Verification', 'main_reason' => 'Expiry', 'remarks' => 'Inner seal broken during transport by van driver; shopkeeper refused acceptance.', 'condition' => 'Damaged [Send to Distributor Claim]', 'credit_note' => 'CN-2026-102', 'impact' => 'Adjusted in shortage balance', 'claim_status' => 'Pending Claim Submission to AAA Traders', 'items' => [['sku' => 'Sooper FP', 'batch' => 'BATCH-2026-042', 'quantity' => '2 Cartons', 'rate' => 2400, 'line_total' => 4800, 'reason' => 'Expired Product'], ['sku' => 'Rio Chocolate', 'batch' => 'BATCH-2026-061', 'quantity' => '1 Carton, 4 Packs', 'rate' => 1800, 'line_total' => 2250, 'reason' => 'Damaged Packaging'], ['sku' => 'Gluco Family', 'batch' => 'BATCH-2026-053', 'quantity' => '2 Cartons', 'rate' => 2483.33, 'line_total' => 7450, 'reason' => 'Wrong Item Delivered']]],
            ['id' => 2, 'return_ref' => 'RET-2026-09-002', 'date' => '03-Sep-2026', 'trip_id' => 2, 'trip_display' => 'TR-2026-09-02-002', 'invoice_ref' => 'INV-8893', 'shop' => 'City Mart', 'market' => 'Saddar', 'distributor' => 'AAA Traders', 'deliveryman' => 'Bilal Raza', 'return_type' => 'Damage In Transit', 'units' => '3 Cartons', 'value' => 8200, 'status' => 'Sent to Distributor', 'main_reason' => 'Damage', 'remarks' => 'Outer cartons crushed during delivery and held separately for review.', 'condition' => 'Damaged [Send to Distributor Claim]', 'credit_note' => 'Pending', 'impact' => 'Deducted from sales', 'claim_status' => 'Submitted to AAA Traders', 'items' => [['sku' => 'Pepsi 1.5L', 'batch' => 'BATCH-2026-070', 'quantity' => '3 Cartons', 'rate' => 2733.33, 'line_total' => 8200, 'reason' => 'Damage In Transit']]],
            ['id' => 3, 'return_ref' => 'RET-2026-09-003', 'date' => '02-Sep-2026', 'trip_id' => 3, 'trip_display' => 'TR-2026-09-01-001', 'invoice_ref' => 'INV-8891', 'shop' => 'Main Bazaar Store', 'market' => 'North Nazimabad', 'distributor' => 'AAA Traders', 'deliveryman' => 'Usman Tariq', 'return_type' => 'Market Return', 'units' => '2 Cartons', 'value' => 5600, 'status' => 'Credit Note Issued', 'main_reason' => 'Market Refusal', 'remarks' => 'Shop received the wrong size and returned unopened goods.', 'condition' => 'Good Condition [Re-stockable]', 'credit_note' => 'CN-2026-101', 'impact' => 'Adjusted in shortage balance', 'claim_status' => 'Credit note received from AAA Traders', 'items' => [['sku' => 'Rio Chocolate', 'batch' => 'BATCH-2026-061', 'quantity' => '2 Cartons', 'rate' => 2800, 'line_total' => 5600, 'reason' => 'Wrong Item Delivered']]],
        ];
    }
}

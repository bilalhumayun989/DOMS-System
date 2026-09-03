<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    private function skus(): array
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

    public function index(): View
    {
        $skus = array_map(function ($sku) {
            $sku['stock_status'] = stockStatus($sku['current_stock'], $sku['reorder_point']);

            return $sku;
        }, $this->skus());

        return view('stock.index', compact('skus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'sku_code' => ['required', 'string'],
            'product_name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'reorder_point' => ['required', 'integer', 'min:0'],
        ]);

        return redirect()->route('stock.index')->with('success', 'SKU added successfully.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        abort_unless(collect($this->skus())->contains('id', $id), 404);

        $request->validate([
            'sku_code' => ['required', 'string'],
            'product_name' => ['required', 'string'],
            'category' => ['required', 'string'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'reorder_point' => ['required', 'integer', 'min:0'],
        ]);

        return redirect()->route('stock.index')->with('success', 'SKU updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        abort_unless(collect($this->skus())->contains('id', $id), 404);

        return redirect()->route('stock.index')->with('success', 'SKU deleted successfully.');
    }

    public function show(int $id): View
    {
        $sku = collect($this->skus())->firstWhere('id', $id);
        if (! $sku) {
            abort(404);
        }

        $sku['stock_status'] = stockStatus($sku['current_stock'], $sku['reorder_point']);

        $movements = [
            ['date' => '2025-07-15', 'type' => 'Dispatch', 'trip_id' => 'TR-2025-07-15-001', 'trip_db_id' => 1, 'qty_change' => -24, 'balance_after' => $sku['current_stock']],
            ['date' => '2025-07-14', 'type' => 'Dispatch', 'trip_id' => 'TR-2025-07-14-001', 'trip_db_id' => 6, 'qty_change' => -36, 'balance_after' => $sku['current_stock'] + 24],
            ['date' => '2025-07-14', 'type' => 'Return', 'trip_id' => 'TR-2025-07-14-001', 'trip_db_id' => 6, 'qty_change' => +6, 'balance_after' => $sku['current_stock'] + 36 + 6],
            ['date' => '2025-07-13', 'type' => 'Dispatch', 'trip_id' => 'TR-2025-07-13-001', 'trip_db_id' => 8, 'qty_change' => -48, 'balance_after' => $sku['current_stock'] + 36 + 6 + 48],
            ['date' => '2025-07-10', 'type' => 'Adjustment', 'trip_id' => null, 'trip_db_id' => null, 'qty_change' => +100, 'balance_after' => $sku['current_stock'] + 36 + 6 + 48 + 100],
        ];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Stock', 'route' => route('stock.index')],
            ['label' => $sku['sku_code'], 'route' => null],
        ];

        return view('stock.show', compact('sku', 'movements', 'breadcrumbs'));
    }
}

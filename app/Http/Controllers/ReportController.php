<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = [
            ['title' => 'Trip Report', 'description' => 'Daily trip sheets with load value, collections, and shortage totals grouped by date range.', 'route' => route('reports.trips'), 'icon' => 'truck', 'color' => 'blue'],
            ['title' => 'Deliveryman Report', 'description' => 'Per-deliveryman summary: total trips, value delivered, collections, shortages, and shortage rate.', 'route' => route('reports.deliverymen'), 'icon' => 'users', 'color' => 'indigo'],
            ['title' => 'Market / Customer Report', 'description' => 'Per-market sales, collections, outstanding balance, and aging analysis (0–30 / 31–60 / 60+ days).', 'route' => route('reports.markets'), 'icon' => 'map', 'color' => 'teal'],
            ['title' => 'Stock Report', 'description' => 'Current stock levels, low-stock alerts, and reorder recommendations per SKU and category.', 'route' => route('reports.stock'), 'icon' => 'cube', 'color' => 'amber'],
            ['title' => 'SKU Movement Report', 'description' => 'Full dispatch, return, and adjustment history per SKU with running stock balance.', 'route' => route('reports.sku-movement'), 'icon' => 'chart', 'color' => 'purple'],
            ['title' => 'Bank / Financial', 'description' => 'Daily totals for sales, collections, shortages, expenses, returns, and bank / finance movement for the current month.', 'route' => route('reports.financial-summary'), 'icon' => 'currency', 'color' => 'green'],
            ['title' => 'Audit Trail', 'description' => 'Complete log of all corrections, approvals, user actions, and settlement decisions with timestamps.', 'route' => route('reports.audit-trail'), 'icon' => 'shield', 'color' => 'red'],
        ];

        return view('reports.index', compact('reports'));
    }

    public function trips(Request $request): View
    {
        $rows = [
            ['date' => '2025-07-15', 'trips' => 5, 'load_value' => 487500, 'collected' => 312000, 'shortage' => 7500],
            ['date' => '2025-07-14', 'trips' => 2, 'load_value' => 238000, 'collected' => 175000, 'shortage' => 7000],
            ['date' => '2025-07-13', 'trips' => 2, 'load_value' => 270500, 'collected' => 212000, 'shortage' => 0],
            ['date' => '2025-07-12', 'trips' => 1, 'load_value' => 67800, 'collected' => 52000, 'shortage' => 0],
            ['date' => '2025-07-11', 'trips' => 1, 'load_value' => 88900, 'collected' => 62000, 'shortage' => 0],
        ];
        $selectedDay = (int) $request->query('day', 0);
        if ($selectedDay >= 1 && $selectedDay <= 31) {
            $rows = array_values(array_filter($rows, fn (array $row): bool => (int) date('d', strtotime($row['date'])) === $selectedDay));
        } else {
            $selectedDay = null;
        }

        return view('reports.trips', compact('rows', 'selectedDay'));
    }

    public function deliverymen(): View
    {
        $rows = [
            ['name' => 'Ahmed Khan', 'total_trips' => 48, 'total_value' => 1850000, 'total_collected' => 1720000, 'total_shortages' => 12500, 'shortage_rate' => 0.7],
            ['name' => 'Bilal Raza', 'total_trips' => 41, 'total_value' => 1480000, 'total_collected' => 1390000, 'total_shortages' => 8000, 'shortage_rate' => 0.5],
            ['name' => 'Usman Tariq', 'total_trips' => 36, 'total_value' => 1220000, 'total_collected' => 1150000, 'total_shortages' => 4000, 'shortage_rate' => 0.3],
            ['name' => 'Zubair Malik', 'total_trips' => 29, 'total_value' => 980000, 'total_collected' => 960000, 'total_shortages' => 0, 'shortage_rate' => 0.0],
            ['name' => 'Kashif Hussain', 'total_trips' => 22, 'total_value' => 720000, 'total_collected' => 710000, 'total_shortages' => 0, 'shortage_rate' => 0.0],
        ];

        return view('reports.deliverymen', compact('rows'));
    }

    public function financialSummary(): View
    {
        $rows = [
            ['date' => '2025-07-15', 'sales' => 487500, 'collections' => 312000, 'shortages' => 7500, 'returns' => 8200, 'expenses' => 3500],
            ['date' => '2025-07-14', 'sales' => 238000, 'collections' => 175000, 'shortages' => 7000, 'returns' => 4800, 'expenses' => 2800],
            ['date' => '2025-07-13', 'sales' => 270500, 'collections' => 212000, 'shortages' => 0, 'returns' => 6100, 'expenses' => 4200],
            ['date' => '2025-07-12', 'sales' => 67800, 'collections' => 52000, 'shortages' => 0, 'returns' => 1200, 'expenses' => 1800],
            ['date' => '2025-07-11', 'sales' => 88900, 'collections' => 62000, 'shortages' => 0, 'returns' => 2900, 'expenses' => 2100],
        ];

        return view('reports.financial-summary', compact('rows'));
    }

    public function markets(): View
    {
        $rows = [
            ['name' => 'Gulshan-e-Iqbal', 'total_invoices' => 18, 'total_sales' => 485000, 'total_collected' => 420000, 'outstanding' => 65000, 'aging_0_30' => 26000, 'aging_31_60' => 28000, 'aging_60_plus' => 11000],
            ['name' => 'North Nazimabad', 'total_invoices' => 15, 'total_sales' => 362000, 'total_collected' => 310000, 'outstanding' => 52000, 'aging_0_30' => 18000, 'aging_31_60' => 22000, 'aging_60_plus' => 12000],
            ['name' => 'Korangi Industrial', 'total_invoices' => 20, 'total_sales' => 612000, 'total_collected' => 570000, 'outstanding' => 42000, 'aging_0_30' => 42000, 'aging_31_60' => 0, 'aging_60_plus' => 0],
        ];

        return view('reports.markets', compact('rows'));
    }

    public function stock(): View
    {
        $rows = [
            ['category' => 'Beverages', 'total_skus' => 6, 'in_stock' => 3, 'low_stock' => 2, 'out_of_stock' => 1, 'total_units' => 1597],
            ['category' => 'Snacks', 'total_skus' => 7, 'in_stock' => 4, 'low_stock' => 2, 'out_of_stock' => 1, 'total_units' => 1236],
            ['category' => 'Household', 'total_skus' => 7, 'in_stock' => 5, 'low_stock' => 2, 'out_of_stock' => 0, 'total_units' => 1190],
        ];

        return view('reports.stock', compact('rows'));
    }

    public function skuMovement(): View
    {
        $rows = [
            ['date' => '2025-07-15', 'sku' => 'BEV-001', 'product' => 'Pepsi 1.5L', 'dispatched' => 48, 'returned' => 6, 'adjusted' => 0, 'net_movement' => -42],
            ['date' => '2025-07-15', 'sku' => 'SNK-001', 'product' => 'Lays Classic 100g', 'dispatched' => 60, 'returned' => 0, 'adjusted' => 0, 'net_movement' => -60],
            ['date' => '2025-07-14', 'sku' => 'BEV-002', 'product' => 'Coca-Cola 1.5L', 'dispatched' => 36, 'returned' => 12, 'adjusted' => 0, 'net_movement' => -24],
            ['date' => '2025-07-14', 'sku' => 'HH-001', 'product' => 'Surf Excel 500g', 'dispatched' => 24, 'returned' => 8, 'adjusted' => 0, 'net_movement' => -16],
        ];

        return view('reports.sku-movement', compact('rows'));
    }

    public function auditTrail(): View
    {
        $rows = [
            ['timestamp' => '2025-07-15 16:32:00', 'user' => 'Admin', 'action' => 'Settlement Classified', 'entity' => 'TR-2025-07-14-001', 'details' => 'Shortage PKR 7,000 classified as Deliveryman Short', 'approved_by' => 'Manager'],
            ['timestamp' => '2025-07-15 14:10:00', 'user' => 'Operator1', 'action' => 'Collection Entered', 'entity' => 'INV-010', 'details' => 'Cash collection PKR 55,000 from Clifton Mart', 'approved_by' => null],
            ['timestamp' => '2025-07-14 18:45:00', 'user' => 'Warehouse1', 'action' => 'Returns Verified', 'entity' => 'RET-004', 'details' => 'Surf Excel 500g x8 restocked — REFUSED', 'approved_by' => 'Warehouse Supervisor'],
            ['timestamp' => '2025-07-14 09:00:00', 'user' => 'Operator1', 'action' => 'Trip Dispatched', 'entity' => 'TR-2025-07-14-001', 'details' => 'Ahmed Khan dispatched to Clifton, Load PKR 143,000', 'approved_by' => null],
        ];

        return view('reports.audit-trail', compact('rows'));
    }
}

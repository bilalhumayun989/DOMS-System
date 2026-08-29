<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InvoiceController extends Controller
{
    private function invoices(): array
    {
        return [
            ['id' => 1, 'invoice_number' => 'INV-001', 'customer' => 'Al-Noor General Store', 'market_id' => 1, 'trip_id' => 1, 'trip_id_display' => 'TR-2025-07-15-001', 'date' => '2025-07-15', 'total_value' => 35000, 'status' => 'DELIVERED'],
            ['id' => 2, 'invoice_number' => 'INV-002', 'customer' => 'City Mart', 'market_id' => 1, 'trip_id' => 1, 'trip_id_display' => 'TR-2025-07-15-001', 'date' => '2025-07-15', 'total_value' => 48000, 'status' => 'PARTIAL'],
            ['id' => 3, 'invoice_number' => 'INV-003', 'customer' => 'Gulshan Traders', 'market_id' => 1, 'trip_id' => 1, 'trip_id_display' => 'TR-2025-07-15-001', 'date' => '2025-07-15', 'total_value' => 42400, 'status' => 'NOT DELIVERED'],
            ['id' => 4, 'invoice_number' => 'INV-004', 'customer' => 'Hassan Brothers', 'market_id' => 2, 'trip_id' => 2, 'trip_id_display' => 'TR-2025-07-15-002', 'date' => '2025-07-15', 'total_value' => 27000, 'status' => 'DELIVERED'],
            ['id' => 5, 'invoice_number' => 'INV-005', 'customer' => 'Metro Supplies', 'market_id' => 2, 'trip_id' => 2, 'trip_id_display' => 'TR-2025-07-15-002', 'date' => '2025-07-15', 'total_value' => 38500, 'status' => 'DELIVERED'],
            ['id' => 6, 'invoice_number' => 'INV-006', 'customer' => 'Nazimabad Store', 'market_id' => 2, 'trip_id' => 2, 'trip_id_display' => 'TR-2025-07-15-002', 'date' => '2025-07-15', 'total_value' => 33200, 'status' => 'PARTIAL'],
            ['id' => 7, 'invoice_number' => 'INV-007', 'customer' => 'Pak Traders', 'market_id' => 4, 'trip_id' => 3, 'trip_id_display' => 'TR-2025-07-15-003', 'date' => '2025-07-15', 'total_value' => 29000, 'status' => 'DELIVERED'],
            ['id' => 8, 'invoice_number' => 'INV-008', 'customer' => 'Orangi Mart', 'market_id' => 4, 'trip_id' => 3, 'trip_id_display' => 'TR-2025-07-15-003', 'date' => '2025-07-15', 'total_value' => 31500, 'status' => 'RESERVICE'],
            ['id' => 9, 'invoice_number' => 'INV-009', 'customer' => 'Town Stores', 'market_id' => 4, 'trip_id' => 3, 'trip_id_display' => 'TR-2025-07-15-003', 'date' => '2025-07-15', 'total_value' => 26800, 'status' => 'DELIVERED'],
            ['id' => 10, 'invoice_number' => 'INV-010', 'customer' => 'Clifton Mart', 'market_id' => 8, 'trip_id' => 6, 'trip_id_display' => 'TR-2025-07-14-001', 'date' => '2025-07-14', 'total_value' => 55000, 'status' => 'DELIVERED'],
            ['id' => 11, 'invoice_number' => 'INV-011', 'customer' => 'Sea View Stores', 'market_id' => 8, 'trip_id' => 6, 'trip_id_display' => 'TR-2025-07-14-001', 'date' => '2025-07-14', 'total_value' => 48000, 'status' => 'DELIVERED'],
            ['id' => 12, 'invoice_number' => 'INV-012', 'customer' => 'SITE Traders', 'market_id' => 6, 'trip_id' => 7, 'trip_id_display' => 'TR-2025-07-14-002', 'date' => '2025-07-14', 'total_value' => 40000, 'status' => 'DELIVERED'],
            ['id' => 13, 'invoice_number' => 'INV-013', 'customer' => 'Industrial Mart', 'market_id' => 5, 'trip_id' => 8, 'trip_id_display' => 'TR-2025-07-13-001', 'date' => '2025-07-13', 'total_value' => 68000, 'status' => 'PARTIAL'],
            ['id' => 14, 'invoice_number' => 'INV-014', 'customer' => 'Korangi Supplies', 'market_id' => 5, 'trip_id' => 8, 'trip_id_display' => 'TR-2025-07-13-001', 'date' => '2025-07-13', 'total_value' => 72000, 'status' => 'DELIVERED'],
            ['id' => 15, 'invoice_number' => 'INV-015', 'customer' => 'Factory Store', 'market_id' => 5, 'trip_id' => 8, 'trip_id_display' => 'TR-2025-07-13-001', 'date' => '2025-07-13', 'total_value' => 38500, 'status' => 'NOT DELIVERED'],
            ['id' => 16, 'invoice_number' => 'INV-016', 'customer' => 'Gulshan Traders 2', 'market_id' => 1, 'trip_id' => 9, 'trip_id_display' => 'TR-2025-07-13-002', 'date' => '2025-07-13', 'total_value' => 44000, 'status' => 'DELIVERED'],
            ['id' => 17, 'invoice_number' => 'INV-017', 'customer' => 'Nazim Stores', 'market_id' => 2, 'trip_id' => 10, 'trip_id_display' => 'TR-2025-07-12-001', 'date' => '2025-07-12', 'total_value' => 29000, 'status' => 'DELIVERED'],
            ['id' => 18, 'invoice_number' => 'INV-018', 'customer' => 'Al-Raheem Traders', 'market_id' => 3, 'trip_id' => 10, 'trip_id_display' => 'TR-2025-07-12-001', 'date' => '2025-07-12', 'total_value' => 38800, 'status' => 'PARTIAL'],
            ['id' => 19, 'invoice_number' => 'INV-019', 'customer' => 'Saddar Bazaar', 'market_id' => 7, 'trip_id' => 11, 'trip_id_display' => 'TR-2025-07-11-001', 'date' => '2025-07-11', 'total_value' => 52000, 'status' => 'DELIVERED'],
            ['id' => 20, 'invoice_number' => 'INV-020', 'customer' => 'Empress Market', 'market_id' => 7, 'trip_id' => 11, 'trip_id_display' => 'TR-2025-07-11-001', 'date' => '2025-07-11', 'total_value' => 36900, 'status' => 'DELIVERED'],
        ];
    }

    public function index(): View
    {
        $invoices = $this->invoices();
        return view('invoices.index', compact('invoices'));
    }

    public function show(int $id): View
    {
        $invoice = collect($this->invoices())->firstWhere('id', $id);
        if (! $invoice) abort(404);

        $lineItems = [
            ['sku' => 'BEV-001', 'product' => 'Pepsi 1.5L', 'ordered_qty' => 24, 'delivered_qty' => 24, 'unit_price' => 120, 'line_total' => 2880],
            ['sku' => 'BEV-003', 'product' => 'Nestle Water 1.5L', 'ordered_qty' => 48, 'delivered_qty' => 48, 'unit_price' => 55, 'line_total' => 2640],
            ['sku' => 'SNK-002', 'product' => 'Kurkure 70g', 'ordered_qty' => 60, 'delivered_qty' => 60, 'unit_price' => 30, 'line_total' => 1800],
            ['sku' => 'HH-005', 'product' => 'Surf Excel 500g', 'ordered_qty' => 24, 'delivered_qty' => 24, 'unit_price' => 180, 'line_total' => 4320],
        ];

        $collections = [
            ['date' => '2025-07-15', 'amount' => 35000, 'method' => 'Cash', 'ref' => 'RCP-' . str_pad($id, 4, '0', STR_PAD_LEFT)],
        ];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Invoices', 'route' => route('invoices.index')],
            ['label' => $invoice['invoice_number'], 'route' => null],
        ];

        return view('invoices.show', compact('invoice', 'lineItems', 'collections', 'breadcrumbs'));
    }
}

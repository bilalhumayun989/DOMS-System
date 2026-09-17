<?php

namespace App\Http\Controllers;

use App\Models\Deliveryman;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Market;
use App\Models\ReturnClaim;
use App\Models\StockItem;
use App\Models\Trip;
use App\Models\TripCollection;
use App\Models\TripExpense;
use App\Models\TripSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
        $rows = Trip::whereBetween('trip_date', [now()->startOfMonth(), now()->endOfMonth()])->with(['collections', 'settlement'])->orderByDesc('trip_date')->get()->groupBy(fn (Trip $trip): string => $trip->trip_date->toDateString())->map(fn ($trips, $date): array => [
            'date' => $date, 'trips' => $trips->count(), 'load_value' => (float) $trips->sum('load_value'),
            'collected' => (float) $trips->sum(fn (Trip $trip): float => (float) $trip->collections->sum('amount')),
            'shortage' => (float) $trips->sum(fn (Trip $trip): float => max(0, (float) ($trip->settlement?->difference_amount ?? 0))),
        ])->values()->all();
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
        $rows = Deliveryman::get()->map(function (Deliveryman $driver): array {
            $trips = Trip::where('deliveryman_id', $driver->id)->get();
            $value = (float) $trips->sum('load_value');
            $shortages = (float) TripSettlement::whereIn('trip_id', $trips->pluck('id'))->where('difference_amount', '>', 0)->sum('difference_amount');

            return ['name' => $driver->name, 'total_trips' => $trips->count(), 'total_value' => $value,
                'total_collected' => (float) TripCollection::whereIn('trip_id', $trips->pluck('id'))->sum('amount'),
                'total_shortages' => $shortages, 'shortage_rate' => $value > 0 ? round($shortages / $value * 100, 2) : 0];
        })->all();

        return view('reports.deliverymen', compact('rows'));
    }

    public function financialSummary(): View
    {
        $dates = collect()->merge(Invoice::pluck('date'))->merge(TripCollection::pluck('collected_at')->map(fn ($date): string => substr((string) $date, 0, 10)))
            ->merge(Expense::pluck('date'))->merge(ReturnClaim::pluck('date'))->unique()->filter(fn ($date): bool => substr($date, 0, 7) === now()->format('Y-m'))->sortDesc();
        $rows = $dates->map(fn ($date): array => [
            'date' => $date, 'sales' => (float) Invoice::whereDate('date', $date)->sum('total_value'),
            'collections' => (float) TripCollection::whereDate('collected_at', $date)->sum('amount'),
            'shortages' => (float) TripSettlement::whereDate('settled_at', $date)->where('difference_amount', '>', 0)->sum('difference_amount'),
            'returns' => (float) ReturnClaim::whereDate('date', $date)->sum('value'),
            'expenses' => (float) Expense::whereDate('date', $date)->sum('amount') + (float) TripExpense::whereDate('expense_date', $date)->whereNotIn('expense_ref', Expense::select('expense_id'))->sum('amount'),
        ])->values()->all();

        return view('reports.financial-summary', compact('rows'));
    }

    public function markets(): View
    {
        $rows = Market::get()->map(function (Market $market): array {
            $invoices = Invoice::where('market_id', $market->id)->get();
            $collected = (float) TripCollection::whereIn('invoice_number', $invoices->pluck('invoice_number'))->sum('amount');
            $aging = [0, 0, 0];
            foreach ($invoices as $invoice) {
                $age = (int) Carbon::parse($invoice->date)->diffInDays(now());
                $balance = max(0, $invoice->total_value - (float) TripCollection::where('invoice_number', $invoice->invoice_number)->sum('amount'));
                $aging[$age <= 30 ? 0 : ($age <= 60 ? 1 : 2)] += $balance;
            }

            return ['name' => $market->name, 'total_invoices' => $invoices->count(), 'total_sales' => (float) $invoices->sum('total_value'),
                'total_collected' => $collected, 'outstanding' => array_sum($aging) + $market->outstanding_balance,
                'aging_0_30' => $aging[0], 'aging_31_60' => $aging[1], 'aging_60_plus' => $aging[2] + $market->outstanding_balance];
        })->all();

        return view('reports.markets', compact('rows'));
    }

    public function stock(): View
    {
        $rows = StockItem::get()->groupBy('category')->map(fn ($items, $category): array => [
            'category' => $category, 'total_skus' => $items->count(),
            'in_stock' => $items->filter(fn ($item): bool => stockStatus($item->current_stock, $item->reorder_point) === 'In Stock')->count(),
            'low_stock' => $items->filter(fn ($item): bool => stockStatus($item->current_stock, $item->reorder_point) === 'Low Stock')->count(),
            'out_of_stock' => $items->where('current_stock', 0)->count(), 'total_units' => $items->sum('current_stock'),
        ])->values()->all();

        return view('reports.stock', compact('rows'));
    }

    public function skuMovement(): View
    {
        $rows = DB::table('stock_movements')->latest('id')->get()->map(fn ($row): array => [
            'date' => substr($row->created_at, 0, 10), 'sku' => $row->sku, 'product' => $row->product,
            'dispatched' => 0, 'returned' => 0, 'adjusted' => $row->quantity_change, 'net_movement' => $row->quantity_change,
        ])->all();

        return view('reports.sku-movement', compact('rows'));
    }

    public function auditTrail(): View
    {
        $rows = DB::table('audit_logs')->latest('id')->limit(250)->get()->map(fn ($row): array => [
            'timestamp' => $row->created_at, 'user' => $row->user, 'action' => $row->action, 'entity' => $row->entity,
            'details' => $row->details, 'approved_by' => null,
        ])->all();

        return view('reports.audit-trail', compact('rows'));
    }
}

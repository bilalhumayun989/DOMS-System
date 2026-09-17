<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\Ledger;
use App\Models\ReturnClaim;
use App\Models\StockItem;
use App\Models\Trip;
use App\Models\TripCollection;
use App\Models\TripSettlement;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $banks = BankAccount::with('transactions')->get();
        $balance = fn (BankAccount $bank): float => $bank->opening + $bank->transactions->sum(fn (BankTransaction $transaction): float => $transaction->type === 'Deposit / Credit' ? $transaction->amount : -$transaction->amount);
        $supplierBalance = (float) Ledger::where('ledger_group', 'Supplier')->where('entry_type', 'Debit')->sum('amount') - (float) Ledger::where('ledger_group', 'Supplier')->where('entry_type', 'Credit')->sum('amount');
        $kpiCards = [
            ['title' => 'Total Opening Balance', 'value' => pkr($banks->sum('opening')), 'icon' => 'currency', 'color' => 'blue', 'route' => route('banks.index')],
            ['title' => 'Bank Balance', 'value' => pkr($banks->where('type', '!=', 'Cash Account')->sum($balance)), 'icon' => 'banknotes', 'color' => 'green', 'route' => route('banks.index')],
            ['title' => 'Supplier Ledger Balance', 'value' => pkr($supplierBalance), 'icon' => 'currency', 'color' => 'amber', 'route' => route('ledgers.index')],
            ['title' => 'Cash In Hand', 'value' => pkr($banks->where('type', 'Cash Account')->sum($balance)), 'icon' => 'banknotes', 'color' => 'blue', 'route' => route('banks.index')],
            ['title' => 'Stock Units', 'value' => number_format(StockItem::sum('current_stock')), 'icon' => 'cube', 'color' => 'green', 'route' => route('stock.index')],
            ['title' => 'Collections Today', 'value' => pkr((float) TripCollection::whereDate('collected_at', today())->sum('amount')), 'icon' => 'currency', 'color' => 'red', 'route' => route('collections.index')],
            ['title' => 'Pending Claims', 'value' => pkr((float) ReturnClaim::where('status', '!=', 'Credit Note Issued')->sum('value')), 'icon' => 'warning', 'color' => 'amber', 'route' => route('returns.index')],
            ['title' => 'Total SKUs', 'value' => (string) StockItem::count(), 'icon' => 'cube', 'color' => 'blue', 'route' => route('stock.index')],
        ];
        $chartDays = [];
        $chartData = [];
        $collectionChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = today()->subDays($i);
            $chartDays[] = $day->format('D');
            $chartData[] = Trip::whereDate('trip_date', $day)->count();
            $collectionChartData[] = (float) TripCollection::whereDate('collected_at', $day)->sum('amount');
        }
        $todaysTrips = Trip::latest('trip_date')->limit(12)->get()->map(fn (Trip $trip): array => [
            'id' => $trip->id, 'route_id' => $trip->trip_number, 'deliveryman' => $trip->deliveryman_name,
            'deliveryman_id' => $trip->deliveryman_id, 'distributor' => 'AAA Traders', 'market_area' => $trip->market_area,
            'date' => $trip->trip_date->format('d-m-Y'), 'status' => $trip->status,
        ])->all();
        $recentUpdates = DB::table('audit_logs')->latest('id')->limit(12)->get()->map(fn ($row): array => [
            'title' => $row->action.' '.$row->entity, 'desc' => $row->details, 'time' => $row->created_at,
            'type' => 'trip', 'icon_bg' => 'bg-slate-100 text-slate-600',
        ])->all();
        $topShortages = TripSettlement::with('trip')->where('difference_amount', '>', 0)->orderByDesc('difference_amount')->limit(12)->get()->map(fn (TripSettlement $settlement): array => [
            'id' => $settlement->trip_id, 'deliveryman' => $settlement->trip->deliveryman_name, 'deliveryman_id' => $settlement->trip->deliveryman_id,
            'trip_id' => $settlement->trip->trip_number, 'market_area' => $settlement->trip->market_area,
            'amount' => (float) $settlement->difference_amount, 'recovery_status' => 'Pending',
        ])->all();

        return view('dashboard.index', compact('kpiCards', 'todaysTrips', 'topShortages', 'chartDays', 'chartData', 'collectionChartData', 'recentUpdates'));
    }
}

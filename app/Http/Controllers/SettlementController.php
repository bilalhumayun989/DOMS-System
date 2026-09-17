<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripSettlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementController extends Controller
{
    public function index(): View
    {
        $settlements = TripSettlement::with('trip')->latest('settled_at')->get()->map(fn (TripSettlement $settlement): array => $this->present($settlement))->all();
        $totalSettlements = collect($settlements)->sum('net_sales');
        $totalCash = collect($settlements)->sum('actual_cash');
        $totalCredit = collect($settlements)->sum('credit');
        $totalShortage = collect($settlements)->sum('shortage');

        return view('settlements.index', compact('settlements', 'totalSettlements', 'totalCash', 'totalCredit', 'totalShortage'));
    }

    public function create(): View
    {
        $tripOptions = Trip::where('status', '!=', 'CLOSED')->with(['collections', 'expenses'])->get()->map(fn (Trip $trip): array => [
            'id' => $trip->id, 'number' => $trip->trip_number, 'date' => $trip->trip_date->toDateString(),
            'driver' => $trip->deliveryman_name, 'vehicle' => $trip->vehicle, 'market' => $trip->market_area,
            'distributor' => 'AAA Traders', 'net_sales' => (float) $trip->expected_cash, 'credit' => 0,
            'collections' => (float) $trip->collections->sum('amount'), 'expenses' => (float) $trip->expenses->sum('amount'),
        ])->all();

        return view('settlements.create', compact('tripOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['trip_id' => ['required', 'integer', 'exists:trips,id']]);
        $trip = Trip::findOrFail($request->input('trip_id'));
        app(TripController::class)->close($request, $trip);

        return to_route('settlements.show', $trip->settlement->id)->with('success', 'Settlement saved and trip closed.');
    }

    public function show(TripSettlement $settlement): View
    {
        return view('settlements.show', ['record' => $this->present($settlement)]);
    }

    private function present(TripSettlement $settlement): array
    {
        $trip = $settlement->trip;
        $difference = (float) $settlement->difference_amount;

        return ['id' => $settlement->id, 'settlement_ref' => 'SET-'.str_pad((string) $settlement->id, 5, '0', STR_PAD_LEFT),
            'date' => $settlement->settled_at->toDateString(), 'trip_display' => $trip->trip_number, 'trip_id' => $trip->id,
            'deliveryman' => $trip->deliveryman_name, 'vehicle' => $trip->vehicle, 'market' => $trip->market_area, 'distributor' => 'AAA Traders',
            'net_sales' => (float) $settlement->expected_cash, 'expected_cash' => (float) $settlement->expected_cash - (float) $settlement->expense_amount,
            'actual_cash' => (float) $settlement->collected_amount, 'shortage' => max(0, $difference), 'excess' => max(0, -$difference),
            'status' => abs($difference) < 0.01 ? 'Fully Cleared' : 'Shortage Flagged', 'loaded' => (float) $trip->load_value,
            'returned' => 0, 'damaged' => 0, 'discounts' => 0, 'credit' => 0,
            'cheques' => (float) $trip->collections()->where('method', 'Cheque')->sum('amount'),
            'transfers' => (float) $trip->collections()->where('method', 'Transfer')->sum('amount'),
            'action' => $settlement->shortage_classification ?? 'None', 'settled_by' => 'Admin'];
    }
}

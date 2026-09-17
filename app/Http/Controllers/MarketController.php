<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Market;
use App\Models\Trip;
use App\Models\TripCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketController extends Controller
{
    private function markets(): array
    {
        return Market::orderBy('id')->get()->map(function (Market $market): array {
            $invoices = Invoice::where('market_id', $market->id)->get();
            $collected = TripCollection::whereIn('invoice_number', $invoices->pluck('invoice_number'))->sum('amount');

            return [...$market->toArray(), 'total_invoices' => $invoices->count(), 'total_value' => (float) $invoices->sum('total_value'), 'total_collected' => (float) $collected, 'opening_balance' => $market->outstanding_balance, 'outstanding_balance' => $market->outstanding_balance + max(0, (float) $invoices->sum('total_value') - (float) $collected)];
        })->all();
    }

    public function index(): View
    {
        $markets = $this->markets();

        return view('markets.index', compact('markets'));
    }

    public function show(int $id): View
    {
        $market = collect($this->markets())->firstWhere('id', $id);
        if (! $market) {
            abort(404);
        }

        $invoices = Invoice::where('market_id', $id)->get()->map(fn ($invoice): array => [
            'id' => $invoice->id, 'invoice_number' => $invoice->invoice_number, 'date' => $invoice->date,
            'trip_id' => Trip::find($invoice->trip_id)?->trip_number, 'trip_db_id' => $invoice->trip_id,
            'value' => $invoice->total_value, 'collected' => (float) TripCollection::where('invoice_number', $invoice->invoice_number)->sum('amount'), 'status' => $invoice->status,
        ])->all();
        $ledgerEntries = [];

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Markets', 'route' => route('markets.index')],
            ['label' => $market['name'], 'route' => null],
        ];

        return view('markets.show', compact('market', 'invoices', 'ledgerEntries', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        Market::create($this->validatedData($request));

        return to_route('markets.index')->with('success', 'Record created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $record = Market::findOrFail($id);
        $record->update($this->validatedData($request, $id));

        return to_route('markets.index')->with('success', 'Record updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = Market::findOrFail($id);
        abort_if(Invoice::where('market_id', $id)->exists(), 422, 'This market has invoices and cannot be deleted.');
        $record->delete();

        return to_route('markets.index')->with('success', 'Record deleted.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'area' => ['required', 'string', 'max:150'],
            'contact' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'outstanding_balance' => ['required', 'numeric', 'min:0'],
        ]);

        return $data;
    }
}

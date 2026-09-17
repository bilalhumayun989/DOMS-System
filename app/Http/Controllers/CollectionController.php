<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Trip;
use App\Models\TripCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $methodFilter = $request->query('method', 'All');
        $showAll = $request->boolean('show_all_methods', false);

        $query = TripCollection::query()->with('trip')->latest('collected_at');
        if (! $showAll && $methodFilter !== 'All') {
            $query->where('method', $methodFilter);
        }

        $collections = $query->get()->map(fn (TripCollection $collection) => [
            'id' => $collection->id,
            'collection_ref' => $collection->collection_ref,
            'date' => $collection->collected_at->toDateString(),
            'customer' => $collection->customer,
            'market_id' => Invoice::where('invoice_number', $collection->invoice_number)->value('market_id'),
            'invoice_number' => $collection->invoice_number,
            'invoice_id' => Invoice::where('invoice_number', $collection->invoice_number)->value('id'),
            'trip_id' => $collection->trip_id,
            'trip_display' => $collection->trip->trip_number,
            'amount' => (float) $collection->amount,
            'method' => $collection->method, 'cheque_number' => $collection->cheque_number, 'bank_name' => $collection->bank_name, 'instrument_date' => $collection->instrument_date?->toDateString(), 'bank_reference' => $collection->bank_reference,
            'deliveryman' => $collection->trip->deliveryman_name,
        ])->all();

        $dailyTotal = collect($collections)->where('date', now()->toDateString())->sum('amount');

        return view('collections.index', compact('collections', 'methodFilter', 'dailyTotal'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['trip_display' => ['required', 'exists:trips,trip_number']]);
        $trip = Trip::where('trip_number', $request->input('trip_display'))->firstOrFail();
        $request->merge(['collected_at' => $request->input('date')]);
        app(TripController::class)->storeCollection($request, $trip);

        return to_route('collections.index')->with('success', 'Collection saved.');
    }

    public function update(Request $request, TripCollection $collection): RedirectResponse
    {
        $request->merge(['collected_at' => $request->input('date')]);
        app(TripController::class)->updateCollection($request, $collection->trip, $collection);

        return to_route('collections.index')->with('success', 'Collection updated.');
    }

    public function destroy(TripCollection $collection): RedirectResponse
    {
        abort_if($collection->trip->isClosed(), 422, 'Closed trips are locked and cannot be edited.');
        $collection->delete();

        return to_route('collections.index')->with('success', 'Collection deleted.');
    }
}

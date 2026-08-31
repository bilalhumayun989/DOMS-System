<?php

namespace App\Http\Controllers;

use App\Models\TripCollection;
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
            'market_id' => 1,
            'invoice_number' => $collection->invoice_number,
            'invoice_id' => 1,
            'trip_id' => $collection->trip_id,
            'trip_display' => $collection->trip->trip_number,
            'amount' => (float) $collection->amount,
            'method' => $collection->method,
            'deliveryman' => $collection->trip->deliveryman_name,
        ])->all();

        $dailyTotal = array_sum(array_column($collections, 'amount'));

        return view('collections.index', compact('collections', 'methodFilter', 'dailyTotal'));
    }
}

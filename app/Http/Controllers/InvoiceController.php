<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Market;
use App\Models\Trip;
use App\Models\TripCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    private function invoices(): array
    {
        return Invoice::orderBy('id')->get()->map(fn (Invoice $invoice): array => [...$invoice->toArray(), 'trip_id_display' => Trip::find($invoice->trip_id)?->trip_number])->all();
    }

    public function index(): View
    {
        $invoices = $this->invoices();

        return view('invoices.index', ['invoices' => $invoices, 'markets' => Market::get(), 'trips' => Trip::get()]);
    }

    public function show(int $id): View
    {
        $invoice = collect($this->invoices())->firstWhere('id', $id);
        if (! $invoice) {
            abort(404);
        }

        $lineItems = [];
        $collections = TripCollection::where('invoice_number', $invoice['invoice_number'])->get()->map(fn ($collection): array => [
            'date' => $collection->collected_at->toDateString(), 'amount' => (float) $collection->amount, 'method' => $collection->method, 'ref' => $collection->collection_ref,
        ])->all();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Invoices', 'route' => route('invoices.index')],
            ['label' => $invoice['invoice_number'], 'route' => null],
        ];

        return view('invoices.show', compact('invoice', 'lineItems', 'collections', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        Invoice::create($this->validatedData($request));

        return to_route('invoices.index')->with('success', 'Record created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $record = Invoice::findOrFail($id);
        $record->update($this->validatedData($request, $id));

        return to_route('invoices.index')->with('success', 'Record updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = Invoice::findOrFail($id);
        abort_if(TripCollection::where('invoice_number', $record->invoice_number)->exists(), 422, 'This invoice has collections and cannot be deleted.');
        $record->delete();

        return to_route('invoices.index')->with('success', 'Record deleted.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'invoice_number' => ['required', 'string', 'max:100', Rule::unique('invoices')->ignore($id)],
            'customer' => ['required', 'string', 'max:150'],
            'trip_id_display' => ['required', 'exists:trips,trip_number'],
            'market_id' => ['nullable', 'integer', 'exists:markets,id'],
            'date' => ['required', 'date'],
            'total_value' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['DELIVERED', 'PARTIAL', 'NOT DELIVERED', 'RESERVICE'])],
        ]);
        $data['trip_id'] = Trip::where('trip_number', $data['trip_id_display'])->firstOrFail()->id;
        unset($data['trip_id_display']);

        return $data;
    }
}

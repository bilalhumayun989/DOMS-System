<?php

namespace App\Http\Controllers;

use App\Models\ReturnClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(): View
    {
        $returns = $this->returnClaims();

        return view('returns.index', compact('returns'));
    }

    public function create(): View
    {
        return view('returns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['return_ref'] = $data['return_ref'] ?: 'RET-'.str()->upper(str()->random(10));
        $claim = ReturnClaim::create($data);

        return to_route('returns.show', $claim->id)->with('success', 'Return claim created.');
    }

    public function show(int $return): View
    {
        $claim = collect($this->returnClaims())->firstWhere('id', $return);
        abort_unless($claim, 404);

        return view('returns.show', compact('claim'));
    }

    public function edit(int $return): View
    {
        $claim = collect($this->returnClaims())->firstWhere('id', $return);
        abort_unless($claim, 404);

        return view('returns.edit', compact('claim'));
    }

    public function update(Request $request, int $return): RedirectResponse
    {
        $claim = ReturnClaim::findOrFail($return);
        $data = $this->validatedData($request);
        $data['return_ref'] = $data['return_ref'] ?: $claim->return_ref;
        $data['items'] = array_merge($data['items'], array_slice($claim->items ?? [], 1));
        $claim->update($data);

        return to_route('returns.show', $return)->with('success', 'Return claim updated.');
    }

    public function destroy(int $return): RedirectResponse
    {
        ReturnClaim::findOrFail($return)->delete();

        return to_route('returns.index')->with('success', 'Return claim deleted.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'return_ref' => ['nullable', 'string', 'max:50'],
            'date' => ['required', 'date'],
            'trip_display' => ['required', 'string', 'max:100'],
            'invoice_ref' => ['required', 'string', 'max:100'],
            'shop' => ['required', 'string', 'max:150'],
            'market' => ['required', 'string', 'max:100'],
            'deliveryman' => ['required', 'string', 'max:100'],
            'distributor' => ['required', 'string', 'max:150'],
            'return_type' => ['required', 'string', 'max:100'],
            'units' => ['required', 'string', 'max:100'],
            'value' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:100'],
            'main_reason' => ['required', 'string', 'max:100'],
            'condition' => ['required', 'string', 'max:150'],
            'remarks' => ['nullable', 'string'],
            'credit_note' => ['nullable', 'string', 'max:100'],
            'impact' => ['required', 'string', 'max:150'],
            'claim_status' => ['required', 'string', 'max:200'],
            'sku' => ['required', 'string', 'max:150'],
            'batch' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric', 'min:0'],
            'line_total' => ['required', 'numeric', 'min:0'],
            'item_reason' => ['required', 'string', 'max:150'],
        ]);

        $validated['items'] = [[
            'sku' => $validated['sku'],
            'batch' => $validated['batch'],
            'quantity' => $validated['quantity'],
            'rate' => $validated['rate'],
            'line_total' => $validated['line_total'],
            'reason' => $validated['item_reason'],
        ]];
        unset($validated['sku'], $validated['batch'], $validated['quantity'], $validated['rate'], $validated['line_total'], $validated['item_reason']);

        return $validated;
    }

    private function returnClaims(): array
    {
        return ReturnClaim::orderBy('id')->get()->toArray();
    }
}

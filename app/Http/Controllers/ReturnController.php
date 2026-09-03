<?php

namespace App\Http\Controllers;

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
        $returns = $this->returnClaims();
        $data['id'] = count($returns) + 1;
        $data['return_ref'] = $data['return_ref'] ?: 'RET-'.now()->format('Y-m').'-'.str_pad((string) $data['id'], 3, '0', STR_PAD_LEFT);
        $returns[] = $data;
        session(['return_claims' => $returns]);

        return redirect()->route('returns.show', $data['id'])->with('success', 'Return claim created successfully.');
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
        $returns = $this->returnClaims();
        $index = collect($returns)->search(fn (array $claim): bool => $claim['id'] === $return);
        abort_unless($index !== false, 404);
        $data = $this->validatedData($request);
        $data['id'] = $return;
        $data['return_ref'] = $data['return_ref'] ?: $returns[$index]['return_ref'];
        $returns[$index] = array_merge($returns[$index], $data);
        session(['return_claims' => $returns]);

        return redirect()->route('returns.show', $return)->with('success', 'Return claim updated successfully.');
    }

    public function destroy(int $return): RedirectResponse
    {
        $returns = $this->returnClaims();
        $remaining = array_values(array_filter($returns, fn (array $claim): bool => $claim['id'] !== $return));
        abort_unless(count($remaining) !== count($returns), 404);
        session(['return_claims' => $remaining]);

        return redirect()->route('returns.index')->with('success', 'Return claim deleted successfully.');
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
        if (session()->has('return_claims')) {
            return session('return_claims');
        }

        return [
            ['id' => 1, 'return_ref' => 'RET-2026-09-001', 'date' => '03-Sep-2026', 'trip_id' => 1, 'trip_display' => 'TR-2026-09-02-001', 'invoice_ref' => 'INV-8892', 'shop' => 'Al-Noor General Store', 'market' => 'Gulshan-e-Iqbal', 'distributor' => 'AAA Traders', 'deliveryman' => 'Ahmed Khan', 'return_type' => 'Expiry Claim', 'units' => '5 Cartons', 'value' => 14500, 'status' => 'Pending Verification', 'main_reason' => 'Expiry', 'remarks' => 'Inner seal broken during transport by van driver; shopkeeper refused acceptance.', 'condition' => 'Damaged [Send to Distributor Claim]', 'credit_note' => 'CN-2026-102', 'impact' => 'Adjusted in shortage balance', 'claim_status' => 'Pending Claim Submission to AAA Traders', 'items' => [['sku' => 'Sooper FP', 'batch' => 'BATCH-2026-042', 'quantity' => '2 Cartons', 'rate' => 2400, 'line_total' => 4800, 'reason' => 'Expired Product'], ['sku' => 'Rio Chocolate', 'batch' => 'BATCH-2026-061', 'quantity' => '1 Carton, 4 Packs', 'rate' => 1800, 'line_total' => 2250, 'reason' => 'Damaged Packaging'], ['sku' => 'Gluco Family', 'batch' => 'BATCH-2026-053', 'quantity' => '2 Cartons', 'rate' => 2483.33, 'line_total' => 7450, 'reason' => 'Wrong Item Delivered']]],
            ['id' => 2, 'return_ref' => 'RET-2026-09-002', 'date' => '03-Sep-2026', 'trip_id' => 2, 'trip_display' => 'TR-2026-09-02-002', 'invoice_ref' => 'INV-8893', 'shop' => 'City Mart', 'market' => 'Saddar', 'distributor' => 'AAA Traders', 'deliveryman' => 'Bilal Raza', 'return_type' => 'Damage In Transit', 'units' => '3 Cartons', 'value' => 8200, 'status' => 'Sent to Distributor', 'main_reason' => 'Damage', 'remarks' => 'Outer cartons crushed during delivery and held separately for review.', 'condition' => 'Damaged [Send to Distributor Claim]', 'credit_note' => 'Pending', 'impact' => 'Deducted from sales', 'claim_status' => 'Submitted to AAA Traders', 'items' => [['sku' => 'Pepsi 1.5L', 'batch' => 'BATCH-2026-070', 'quantity' => '3 Cartons', 'rate' => 2733.33, 'line_total' => 8200, 'reason' => 'Damage In Transit']]],
            ['id' => 3, 'return_ref' => 'RET-2026-09-003', 'date' => '02-Sep-2026', 'trip_id' => 3, 'trip_display' => 'TR-2026-09-01-001', 'invoice_ref' => 'INV-8891', 'shop' => 'Main Bazaar Store', 'market' => 'North Nazimabad', 'distributor' => 'AAA Traders', 'deliveryman' => 'Usman Tariq', 'return_type' => 'Market Return', 'units' => '2 Cartons', 'value' => 5600, 'status' => 'Credit Note Issued', 'main_reason' => 'Market Refusal', 'remarks' => 'Shop received the wrong size and returned unopened goods.', 'condition' => 'Good Condition [Re-stockable]', 'credit_note' => 'CN-2026-101', 'impact' => 'Adjusted in shortage balance', 'claim_status' => 'Credit note received from AAA Traders', 'items' => [['sku' => 'Rio Chocolate', 'batch' => 'BATCH-2026-061', 'quantity' => '2 Cartons', 'rate' => 2800, 'line_total' => 5600, 'reason' => 'Wrong Item Delivered']]],
        ];
    }
}

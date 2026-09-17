<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockController extends Controller
{
    private function skus(): array
    {
        return StockItem::orderBy('id')->get()->toArray();
    }

    public function index(): View
    {
        $skus = array_map(function ($sku) {
            $sku['stock_status'] = stockStatus($sku['current_stock'], $sku['reorder_point']);

            return $sku;
        }, $this->skus());

        return view('stock.index', compact('skus'));
    }

    public function show(int $id): View
    {
        $sku = collect($this->skus())->firstWhere('id', $id);
        if (! $sku) {
            abort(404);
        }

        $sku['stock_status'] = stockStatus($sku['current_stock'], $sku['reorder_point']);

        $movements = DB::table('stock_movements')->where('stock_item_id', $id)->orderByDesc('id')->get()->map(fn ($row): array => [
            'date' => substr($row->created_at, 0, 10), 'type' => $row->type, 'trip_id' => null, 'trip_db_id' => null,
            'qty_change' => $row->quantity_change, 'balance_after' => $row->balance_after,
        ])->all();

        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('dashboard')],
            ['label' => 'Stock', 'route' => route('stock.index')],
            ['label' => $sku['sku_code'], 'route' => null],
        ];

        return view('stock.show', compact('sku', 'movements', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        StockItem::create($this->validatedData($request));

        return to_route('stock.index')->with('success', 'Record created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $record = StockItem::findOrFail($id);
        $record->update($this->validatedData($request, $id));

        return to_route('stock.index')->with('success', 'Record updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $record = StockItem::findOrFail($id);

        $record->delete();

        return to_route('stock.index')->with('success', 'Record deleted.');
    }

    private function validatedData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'sku_code' => ['required', 'string', 'max:100', Rule::unique('stock_items')->ignore($id)],
            'product_name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'reorder_point' => ['required', 'integer', 'min:0'],
        ]);

        return $data;
    }
}

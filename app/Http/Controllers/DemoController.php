<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DemoController extends Controller
{
    public const DEMO_LIMIT = 10;

    private const FEATURES = [
        'trips', 'deliverymen', 'markets', 'invoices',
        'expenses', 'banks', 'stock', 'returns', 'settlements', 'ledgers', 'collections',
    ];

    /**
     * Enter demo mode — initialises a fresh session and redirects to the demo dashboard.
     * Every visit to /demo resets the session, giving a clean sandbox.
     */
    public function enter(): RedirectResponse
    {
        session([
            'demo_mode' => true,
            'demo_id' => Str::uuid()->toString(),
            'demo_counts' => array_fill_keys(self::FEATURES, 0),
            'demo_data' => $this->initialDemoData(),
        ]);

        return redirect()->route('demo.dashboard');
    }

    /** Exit demo mode — wipe session and redirect to login. */
    public function exit(): RedirectResponse
    {
        session()->forget(['demo_mode', 'demo_id', 'demo_counts', 'demo_data']);

        return redirect()->route('login');
    }

    // ─── Dashboard ────────────────────────────────────────────────────

    public function dashboard(): View
    {
        $this->requireDemo();

        $data = session('demo_data', []);
        $kpiCards = $this->demoKpiCards($data);
        $todaysTrips = array_values(array_map(fn ($t) => [
            'id' => $t['id'],
            'route_id' => $t['route_id'],
            'deliveryman' => $t['deliveryman'],
            'deliveryman_id' => $t['deliveryman_id'],
            'distributor' => $t['distributor'],
            'market_area' => $t['market_area'],
            'date' => $t['date'],
            'status' => $t['status'],
        ], $data['trips'] ?? []));

        $topShortages = [];
        $pageTitle = 'Dashboard';

        return view('demo.dashboard', compact('kpiCards', 'todaysTrips', 'topShortages', 'pageTitle'));
    }

    // ─── Trips ────────────────────────────────────────────────────────

    public function trips(): View
    {
        $this->requireDemo();

        $trips = session('demo_data.trips', []);
        $deliverymen = session('demo_data.deliverymen', []);
        $pageTitle = 'Trips';
        $used = $this->getCount('trips');

        return view('demo.trips', compact('trips', 'deliverymen', 'pageTitle', 'used'));
    }

    public function storeTrip(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('trips')) {
            return back()->with('demo_limit', 'Demo limit reached! You can only create '.self::DEMO_LIMIT.' trips in demo mode.');
        }

        $data = $request->validate([
            'trip_date' => ['required', 'date'],
            'deliveryman_name' => ['required', 'string', 'max:255'],
            'vehicle' => ['required', 'string', 'max:255'],
            'market_area' => ['required', 'string', 'max:255'],
            'load_value' => ['required', 'numeric', 'min:0'],
            'expected_cash' => ['required', 'numeric', 'min:0'],
        ]);

        $trips = session('demo_data.trips', []);
        $sequence = count($trips) + 1;
        $trips[] = [
            'id' => 100 + $sequence,
            'trip_id' => sprintf('TR-%s-%03d', $data['trip_date'], $sequence + 1),
            'route_id' => sprintf('TR-%s-%03d', $data['trip_date'], $sequence + 1),
            'date' => $data['trip_date'],
            'deliveryman' => $data['deliveryman_name'],
            'deliveryman_id' => 1,
            'vehicle' => $data['vehicle'],
            'market_area' => $data['market_area'],
            'distributor' => 'Demo Distributor',
            'load_value' => (float) $data['load_value'],
            'expected_cash' => (float) $data['expected_cash'],
            'status' => 'DISPATCHED',
            'delivery_result' => null,
            'follow_up_date' => null,
            'delivery_notes' => null,
            'collections' => [],
            'expenses' => [],
        ];

        session(['demo_data.trips' => $trips]);
        $this->incrementCount('trips');
        $used = $this->getCount('trips');

        return redirect()->route('demo.trips')
            ->with('success', "Demo trip created! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateTrip(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();

        $data = $request->validate([
            'trip_date' => ['required', 'date'],
            'deliveryman_name' => ['required', 'string', 'max:255'],
            'vehicle' => ['required', 'string', 'max:255'],
            'market_area' => ['required', 'string', 'max:255'],
            'load_value' => ['required', 'numeric', 'min:0'],
            'expected_cash' => ['required', 'numeric', 'min:0'],
        ]);

        $trips = session('demo_data.trips', []);
        foreach ($trips as &$t) {
            if ((int) $t['id'] === (int) $id) {
                $t['date'] = $data['trip_date'];
                $t['deliveryman'] = $data['deliveryman_name'];
                $t['vehicle'] = $data['vehicle'];
                $t['market_area'] = $data['market_area'];
                $t['load_value'] = (float) $data['load_value'];
                $t['expected_cash'] = (float) $data['expected_cash'];
                break;
            }
        }

        session(['demo_data.trips' => $trips]);

        return redirect()->route('demo.trips')->with('success', 'Demo trip updated successfully!');
    }

    public function destroyTrip(int $id): RedirectResponse
    {
        $this->requireDemo();

        $trips = session('demo_data.trips', []);
        $trips = array_values(array_filter($trips, fn ($t) => (int) $t['id'] !== (int) $id));
        session(['demo_data.trips' => $trips]);

        return redirect()->route('demo.trips')->with('success', 'Demo trip deleted successfully!');
    }

    public function tripShow(int $id): View
    {
        $this->requireDemo();

        $trips = session('demo_data.trips', []);
        $trip = collect($trips)->firstWhere('id', $id);
        abort_if(! $trip, 404);

        $pageTitle = ($trip['route_id'] ?? 'Trip').' — Demo';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'route' => route('demo.dashboard')],
            ['label' => 'Trips',     'route' => route('demo.trips')],
            ['label' => $trip['route_id'] ?? 'Trip', 'route' => null],
        ];

        return view('demo.trip-show', compact('trip', 'pageTitle', 'breadcrumbs'));
    }

    // ─── Deliverymen ──────────────────────────────────────────────────

    public function deliverymen(): View
    {
        $this->requireDemo();

        $deliverymen = session('demo_data.deliverymen', []);
        $pageTitle = 'Deliverymen';
        $used = $this->getCount('deliverymen');

        return view('demo.deliverymen', compact('deliverymen', 'pageTitle', 'used'));
    }

    public function storeDeliveryman(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('deliverymen')) {
            return back()->with('demo_limit', 'Demo limit reached for deliverymen ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'vehicle' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
        ]);
        $items = session('demo_data.deliverymen', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'name' => $data['name'],
            'employee_id' => 'EMP-DEMO-'.str_pad(count($items) + 2, 3, '0', STR_PAD_LEFT),
            'vehicle' => $data['vehicle'],
            'area' => $data['area'] ?? 'Demo Area',
            'total_trips' => 0,
        ];

        session(['demo_data.deliverymen' => $items]);
        $this->incrementCount('deliverymen');
        $used = $this->getCount('deliverymen');

        return redirect()->route('demo.deliverymen')
            ->with('success', "Demo deliveryman added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateDeliveryman(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'vehicle' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
        ]);

        $items = session('demo_data.deliverymen', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['name'] = $data['name'];
                $item['vehicle'] = $data['vehicle'];
                $item['area'] = $data['area'] ?? 'Demo Area';
                break;
            }
        }
        session(['demo_data.deliverymen' => $items]);

        return redirect()->route('demo.deliverymen')->with('success', 'Demo deliveryman updated successfully!');
    }

    public function destroyDeliveryman(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.deliverymen', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.deliverymen' => $items]);

        return redirect()->route('demo.deliverymen')->with('success', 'Demo deliveryman deleted successfully!');
    }

    // ─── Markets ──────────────────────────────────────────────────────

    public function markets(): View
    {
        $this->requireDemo();

        $markets = session('demo_data.markets', []);
        $pageTitle = 'Markets';
        $used = $this->getCount('markets');

        return view('demo.markets', compact('markets', 'pageTitle', 'used'));
    }

    public function storeMarket(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('markets')) {
            return back()->with('demo_limit', 'Demo limit reached for markets ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
        ]);
        $items = session('demo_data.markets', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'name' => $data['name'],
            'area' => $data['area'] ?? 'Demo Area',
            'total_trips' => 0,
            'outstanding' => 0.00,
        ];

        session(['demo_data.markets' => $items]);
        $this->incrementCount('markets');
        $used = $this->getCount('markets');

        return redirect()->route('demo.markets')
            ->with('success', "Demo market added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateMarket(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
        ]);

        $items = session('demo_data.markets', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['name'] = $data['name'];
                $item['area'] = $data['area'] ?? 'Demo Area';
                break;
            }
        }
        session(['demo_data.markets' => $items]);

        return redirect()->route('demo.markets')->with('success', 'Demo market updated successfully!');
    }

    public function destroyMarket(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.markets', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.markets' => $items]);

        return redirect()->route('demo.markets')->with('success', 'Demo market deleted successfully!');
    }

    // ─── Banks ────────────────────────────────────────────────────────

    public function banks(): View
    {
        $this->requireDemo();

        return view('demo.banks', [
            'banks' => session('demo_data.banks', []),
            'pageTitle' => 'Banks',
            'used' => $this->getCount('banks'),
        ]);
    }

    public function storeBank(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('banks')) {
            return back()->with('demo_limit', 'Demo limit reached for banks ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'numeric'],
        ]);

        $items = session('demo_data.banks', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'name' => $data['name'],
            'type' => $data['type'],
            'balance' => (float) $data['balance'],
        ];

        session(['demo_data.banks' => $items]);
        $this->incrementCount('banks');
        $used = $this->getCount('banks');

        return redirect()->route('demo.banks')
            ->with('success', "Demo bank added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateBank(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'balance' => ['required', 'numeric'],
        ]);

        $items = session('demo_data.banks', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['name'] = $data['name'];
                $item['type'] = $data['type'];
                $item['balance'] = (float) $data['balance'];
                break;
            }
        }
        session(['demo_data.banks' => $items]);

        return redirect()->route('demo.banks')->with('success', 'Demo bank updated successfully!');
    }

    public function destroyBank(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.banks', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.banks' => $items]);

        return redirect()->route('demo.banks')->with('success', 'Demo bank deleted successfully!');
    }

    // ─── Invoices ─────────────────────────────────────────────────────

    public function invoices(): View
    {
        $this->requireDemo();

        return view('demo.invoices', [
            'invoices' => session('demo_data.invoices', []),
            'pageTitle' => 'Invoices',
            'used' => $this->getCount('invoices'),
        ]);
    }

    public function storeInvoice(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('invoices')) {
            return back()->with('demo_limit', 'Demo limit reached for invoices ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $items = session('demo_data.invoices', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'invoice_number' => 'INV-DEMO-'.str_pad(count($items) + 1, 3, '0', STR_PAD_LEFT),
            'customer' => $data['customer'],
            'amount' => (float) $data['amount'],
            'status' => $data['status'],
            'date' => now()->format('Y-m-d'),
        ];

        session(['demo_data.invoices' => $items]);
        $this->incrementCount('invoices');
        $used = $this->getCount('invoices');

        return redirect()->route('demo.invoices')
            ->with('success', "Demo invoice created! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateInvoice(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $items = session('demo_data.invoices', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['customer'] = $data['customer'];
                $item['amount'] = (float) $data['amount'];
                $item['status'] = $data['status'];
                break;
            }
        }
        session(['demo_data.invoices' => $items]);

        return redirect()->route('demo.invoices')->with('success', 'Demo invoice updated successfully!');
    }

    public function destroyInvoice(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.invoices', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.invoices' => $items]);

        return redirect()->route('demo.invoices')->with('success', 'Demo invoice deleted successfully!');
    }

    // ─── Expenses ─────────────────────────────────────────────────────

    public function expenses(): View
    {
        $this->requireDemo();

        return view('demo.expenses', [
            'expenses' => session('demo_data.expenses', []),
            'pageTitle' => 'Expenses',
            'used' => $this->getCount('expenses'),
        ]);
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('expenses')) {
            return back()->with('demo_limit', 'Demo limit reached for expenses ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $items = session('demo_data.expenses', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'ref' => 'EXP-DEMO-'.str_pad(count($items) + 1, 3, '0', STR_PAD_LEFT),
            'category' => $data['category'],
            'amount' => (float) $data['amount'],
            'date' => now()->format('Y-m-d'),
            'description' => $data['description'] ?? '',
        ];

        session(['demo_data.expenses' => $items]);
        $this->incrementCount('expenses');
        $used = $this->getCount('expenses');

        return redirect()->route('demo.expenses')
            ->with('success', "Demo expense recorded! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateExpense(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $items = session('demo_data.expenses', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['category'] = $data['category'];
                $item['amount'] = (float) $data['amount'];
                $item['description'] = $data['description'] ?? '';
                break;
            }
        }
        session(['demo_data.expenses' => $items]);

        return redirect()->route('demo.expenses')->with('success', 'Demo expense updated successfully!');
    }

    public function destroyExpense(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.expenses', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.expenses' => $items]);

        return redirect()->route('demo.expenses')->with('success', 'Demo expense deleted successfully!');
    }

    // ─── Returns ──────────────────────────────────────────────────────

    public function returns(): View
    {
        $this->requireDemo();

        return view('demo.returns', [
            'returns' => session('demo_data.returns', []),
            'pageTitle' => 'Returns',
            'used' => $this->getCount('returns'),
        ]);
    }

    public function storeReturn(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('returns')) {
            return back()->with('demo_limit', 'Demo limit reached for returns ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $items = session('demo_data.returns', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'ref' => 'RET-DEMO-'.str_pad(count($items) + 1, 3, '0', STR_PAD_LEFT),
            'customer' => $data['customer'],
            'reason' => $data['reason'],
            'amount' => (float) $data['amount'],
            'date' => now()->format('Y-m-d'),
        ];

        session(['demo_data.returns' => $items]);
        $this->incrementCount('returns');
        $used = $this->getCount('returns');

        return redirect()->route('demo.returns')
            ->with('success', "Demo return created! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateReturn(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $items = session('demo_data.returns', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['customer'] = $data['customer'];
                $item['reason'] = $data['reason'];
                $item['amount'] = (float) $data['amount'];
                break;
            }
        }
        session(['demo_data.returns' => $items]);

        return redirect()->route('demo.returns')->with('success', 'Demo return updated successfully!');
    }

    public function destroyReturn(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.returns', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.returns' => $items]);

        return redirect()->route('demo.returns')->with('success', 'Demo return deleted successfully!');
    }

    // ─── Settlements ──────────────────────────────────────────────────

    public function settlements(): View
    {
        $this->requireDemo();

        return view('demo.settlements', [
            'settlements' => session('demo_data.settlements', []),
            'pageTitle' => 'Settlements',
            'used' => $this->getCount('settlements'),
        ]);
    }

    public function storeSettlement(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('settlements')) {
            return back()->with('demo_limit', 'Demo limit reached for settlements ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'party' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:100'],
        ]);

        $items = session('demo_data.settlements', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'ref' => 'SET-DEMO-'.str_pad(count($items) + 1, 3, '0', STR_PAD_LEFT),
            'party' => $data['party'],
            'amount' => (float) $data['amount'],
            'payment_method' => $data['payment_method'],
            'date' => now()->format('Y-m-d'),
        ];

        session(['demo_data.settlements' => $items]);
        $this->incrementCount('settlements');
        $used = $this->getCount('settlements');

        return redirect()->route('demo.settlements')
            ->with('success', "Demo settlement added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateSettlement(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'party' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:100'],
        ]);

        $items = session('demo_data.settlements', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['party'] = $data['party'];
                $item['amount'] = (float) $data['amount'];
                $item['payment_method'] = $data['payment_method'];
                break;
            }
        }
        session(['demo_data.settlements' => $items]);

        return redirect()->route('demo.settlements')->with('success', 'Demo settlement updated successfully!');
    }

    public function destroySettlement(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.settlements', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.settlements' => $items]);

        return redirect()->route('demo.settlements')->with('success', 'Demo settlement deleted successfully!');
    }

    // ─── Collections ──────────────────────────────────────────────────

    public function collections(): View
    {
        $this->requireDemo();

        return view('demo.collections', [
            'collections' => session('demo_data.collections', []),
            'pageTitle' => 'Collections',
            'used' => $this->getCount('collections'),
        ]);
    }

    public function storeCollection(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('collections')) {
            return back()->with('demo_limit', 'Demo limit reached for collections ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'collector' => ['required', 'string', 'max:255'],
        ]);

        $items = session('demo_data.collections', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'ref' => 'COL-DEMO-'.str_pad(count($items) + 1, 3, '0', STR_PAD_LEFT),
            'customer' => $data['customer'],
            'amount' => (float) $data['amount'],
            'collector' => $data['collector'],
            'date' => now()->format('Y-m-d'),
        ];

        session(['demo_data.collections' => $items]);
        $this->incrementCount('collections');
        $used = $this->getCount('collections');

        return redirect()->route('demo.collections')
            ->with('success', "Demo collection added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateCollection(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'customer' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'collector' => ['required', 'string', 'max:255'],
        ]);

        $items = session('demo_data.collections', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['customer'] = $data['customer'];
                $item['amount'] = (float) $data['amount'];
                $item['collector'] = $data['collector'];
                break;
            }
        }
        session(['demo_data.collections' => $items]);

        return redirect()->route('demo.collections')->with('success', 'Demo collection updated successfully!');
    }

    public function destroyCollection(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.collections', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.collections' => $items]);

        return redirect()->route('demo.collections')->with('success', 'Demo collection deleted successfully!');
    }

    // ─── Stock ────────────────────────────────────────────────────────

    public function stock(): View
    {
        $this->requireDemo();

        return view('demo.stock', [
            'stockItems' => session('demo_data.stock', []),
            'pageTitle' => 'Stock',
            'used' => $this->getCount('stock'),
        ]);
    }

    public function storeStock(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('stock')) {
            return back()->with('demo_limit', 'Demo limit reached for stock items ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
        ]);
        $items = session('demo_data.stock', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'name' => $data['name'],
            'sku' => $data['sku'] ?? ('SKU-DEMO-'.str_pad(count($items) + 2, 3, '0', STR_PAD_LEFT)),
            'quantity' => (int) $data['quantity'],
            'unit_price' => (float) $data['unit_price'],
            'status' => (int) $data['quantity'] > 0 ? 'In Stock' : 'Out of Stock',
        ];

        session(['demo_data.stock' => $items]);
        $this->incrementCount('stock');
        $used = $this->getCount('stock');

        return redirect()->route('demo.stock')
            ->with('success', "Demo stock item added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateStock(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $items = session('demo_data.stock', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['name'] = $data['name'];
                $item['sku'] = $data['sku'] ?? $item['sku'];
                $item['quantity'] = (int) $data['quantity'];
                $item['unit_price'] = (float) $data['unit_price'];
                $item['status'] = (int) $data['quantity'] > 0 ? 'In Stock' : 'Out of Stock';
                break;
            }
        }
        session(['demo_data.stock' => $items]);

        return redirect()->route('demo.stock')->with('success', 'Demo stock item updated successfully!');
    }

    public function destroyStock(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.stock', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.stock' => $items]);

        return redirect()->route('demo.stock')->with('success', 'Demo stock item deleted successfully!');
    }

    // ─── Ledgers ──────────────────────────────────────────────────────

    public function ledgers(): View
    {
        $this->requireDemo();

        return view('demo.ledgers', [
            'ledgers' => session('demo_data.ledgers', []),
            'pageTitle' => 'Ledgers',
            'used' => $this->getCount('ledgers'),
        ]);
    }

    public function storeLedger(Request $request): RedirectResponse
    {
        $this->requireDemo();

        if ($this->limitReached('ledgers')) {
            return back()->with('demo_limit', 'Demo limit reached for ledgers ('.self::DEMO_LIMIT.').');
        }

        $data = $request->validate([
            'party' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Debit,Credit'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
        $items = session('demo_data.ledgers', []);
        $items[] = [
            'id' => 100 + count($items) + 1,
            'ref' => 'LDG-DEMO-'.str_pad(count($items) + 1, 3, '0', STR_PAD_LEFT),
            'party' => $data['party'],
            'type' => $data['type'],
            'amount' => (float) $data['amount'],
            'description' => $data['description'] ?? '',
            'date' => now()->format('Y-m-d'),
        ];

        session(['demo_data.ledgers' => $items]);
        $this->incrementCount('ledgers');
        $used = $this->getCount('ledgers');

        return redirect()->route('demo.ledgers')
            ->with('success', "Demo ledger entry added! ({$used}/".self::DEMO_LIMIT.' uses)');
    }

    public function updateLedger(Request $request, int $id): RedirectResponse
    {
        $this->requireDemo();
        $data = $request->validate([
            'party' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Debit,Credit'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $items = session('demo_data.ledgers', []);
        foreach ($items as &$item) {
            if ((int) $item['id'] === (int) $id) {
                $item['party'] = $data['party'];
                $item['type'] = $data['type'];
                $item['amount'] = (float) $data['amount'];
                $item['description'] = $data['description'] ?? '';
                break;
            }
        }
        session(['demo_data.ledgers' => $items]);

        return redirect()->route('demo.ledgers')->with('success', 'Demo ledger entry updated successfully!');
    }

    public function destroyLedger(int $id): RedirectResponse
    {
        $this->requireDemo();
        $items = session('demo_data.ledgers', []);
        $items = array_values(array_filter($items, fn ($i) => (int) $i['id'] !== (int) $id));
        session(['demo_data.ledgers' => $items]);

        return redirect()->route('demo.ledgers')->with('success', 'Demo ledger entry deleted successfully!');
    }

    // ─── Reports ──────────────────────────────────────────────────────

    public function reports(): View
    {
        $this->requireDemo();

        return view('demo.reports', ['pageTitle' => 'Reports']);
    }

    // ─── Private Helpers ─────────────────────────────────────────────

    private function requireDemo(): void
    {
        if (! session('demo_mode')) {
            session([
                'demo_mode' => true,
                'demo_id' => \Illuminate\Support\Str::uuid()->toString(),
                'demo_counts' => array_fill_keys(self::FEATURES, 0),
                'demo_data' => $this->initialDemoData(),
            ]);
        }
    }

    private function limitReached(string $feature): bool
    {
        return $this->getCount($feature) >= self::DEMO_LIMIT;
    }

    private function getCount(string $feature): int
    {
        return (int) session('demo_counts.'.$feature, 0);
    }

    private function incrementCount(string $feature): void
    {
        $counts = session('demo_counts', []);
        $counts[$feature] = ($counts[$feature] ?? 0) + 1;
        session(['demo_counts' => $counts]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<int, array<string, mixed>>
     */
    private function demoKpiCards(array $data): array
    {
        return [
            ['title' => 'Total Trips',    'value' => count($data['trips'] ?? []),       'icon' => 'truck',    'color' => 'blue',  'route' => route('demo.trips')],
            ['title' => 'Deliverymen',    'value' => count($data['deliverymen'] ?? []),  'icon' => 'cube',     'color' => 'green', 'route' => route('demo.deliverymen')],
            ['title' => 'Markets',        'value' => count($data['markets'] ?? []),      'icon' => 'banknotes', 'color' => 'amber', 'route' => route('demo.markets')],
            ['title' => 'Stock Items',    'value' => count($data['stock'] ?? []),        'icon' => 'cube',     'color' => 'red',   'route' => route('demo.stock')],
            ['title' => 'Invoices',       'value' => count($data['invoices'] ?? []),     'icon' => 'currency', 'color' => 'blue',  'route' => route('demo.invoices')],
            ['title' => 'Expenses',       'value' => count($data['expenses'] ?? []),     'icon' => 'warning',  'color' => 'amber', 'route' => route('demo.expenses')],
            ['title' => 'Returns',        'value' => count($data['returns'] ?? []),      'icon' => 'banknotes', 'color' => 'red',   'route' => route('demo.returns')],
            ['title' => 'Ledger Entries', 'value' => count($data['ledgers'] ?? []),      'icon' => 'currency', 'color' => 'green', 'route' => route('demo.ledgers')],
        ];
    }

    /** @return array<string, array<int, array<string, mixed>>> */
    private function initialDemoData(): array
    {
        $today = now()->format('Y-m-d');

        return [
            'trips' => [
                [
                    'id' => 1,
                    'trip_id' => "TR-{$today}-001",
                    'route_id' => "TR-{$today}-001",
                    'date' => $today,
                    'deliveryman' => 'Ahmed Khan (Demo)',
                    'deliveryman_id' => 1,
                    'vehicle' => 'Toyota Hilux — DEMO-001',
                    'market_area' => 'Demo Market Area',
                    'distributor' => 'Demo Distributor',
                    'source_dlf' => null,
                    'load_value' => 50000.00,
                    'expected_cash' => 48000.00,
                    'status' => 'DISPATCHED',
                    'delivery_result' => null,
                    'follow_up_date' => null,
                    'delivery_notes' => null,
                    'collections' => [],
                    'expenses' => [],
                ],
            ],
            'deliverymen' => [
                ['id' => 1, 'name' => 'Ahmed Khan (Demo)', 'employee_id' => 'EMP-DEMO-001', 'vehicle' => 'Toyota Hilux — DEMO-001', 'area' => 'Demo Area', 'total_trips' => 1],
            ],
            'markets' => [
                ['id' => 1, 'name' => 'Demo Market One', 'area' => 'Demo Market Area', 'total_trips' => 1, 'outstanding' => 2000.00],
            ],
            'invoices' => [
                ['id' => 1, 'invoice_number' => 'INV-DEMO-001', 'customer' => 'Demo Client One', 'amount' => 48000.00, 'status' => 'Unpaid', 'date' => $today],
            ],
            'expenses' => [
                ['id' => 1, 'ref' => 'EXP-DEMO-001', 'category' => 'Fuel', 'amount' => 2000.00, 'date' => $today, 'description' => 'Demo fuel expense for Trip 001'],
            ],
            'banks' => [
                ['id' => 1, 'name' => 'Demo Bank Account', 'balance' => 100000.00, 'type' => 'Current Account'],
            ],
            'stock' => [
                ['id' => 1, 'name' => 'Demo Product Package A', 'sku' => 'SKU-DEMO-001', 'quantity' => 100, 'unit_price' => 500.00, 'status' => 'In Stock'],
            ],
            'returns' => [],
            'settlements' => [],
            'ledgers' => [],
            'collections' => [],
        ];
    }
}

<?php

namespace Tests\Feature;

use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_trip_moves_from_open_to_settlement_then_closes_and_locks(): void
    {
        $trip = Trip::create([
            'trip_number' => 'TR-2026-08-31-099', 'trip_date' => '2026-08-31',
            'deliveryman_name' => 'Ahmed Khan', 'vehicle' => 'ABC-123', 'market_area' => 'Gulshan',
            'status' => 'DISPATCHED', 'load_value' => 120000, 'expected_cash' => 100000,
        ]);

        $this->get(route('trips.index', ['filter' => 'open']))->assertOk()->assertSee($trip->trip_number);

        $this->put(route('trips.delivery-result.update', $trip), [
            'delivery_result' => 'DELAYED', 'follow_up_date' => '2026-09-01',
            'delivery_notes' => 'Customer requested delivery tomorrow.',
        ])->assertRedirect(route('trips.show', $trip));
        $this->assertSame('COMPLETED', $trip->fresh()->status);

        $this->post(route('trips.expenses.store', $trip), [
            'category' => 'Fuel', 'amount' => 5000, 'expense_date' => '2026-08-31',
        ])->assertRedirect(route('trips.show', $trip));

        $this->post(route('trips.collections.store', $trip), [
            'customer' => 'Demo Market', 'invoice_number' => 'INV-099', 'amount' => 90000,
            'method' => 'Cash', 'collected_at' => '2026-08-31 12:00:00',
        ])->assertRedirect(route('trips.show', $trip));

        $this->assertSame('SETTLEMENT PENDING', $trip->fresh()->status);
        $this->get(route('trips.index', ['filter' => 'open']))->assertOk()->assertSee($trip->trip_number);
        $this->get(route('trips.show', $trip))->assertOk()->assertSee('Demo Market')->assertSee('Fuel');

        $this->post(route('trips.close', $trip), [
            'shortage_classification' => 'MARKET SHORT', 'notes' => 'PKR 5,000 remains with market.',
        ])->assertRedirect(route('trips.show', $trip));

        $this->assertSame('CLOSED', $trip->fresh()->status);
        $this->get(route('trips.index', ['filter' => 'open']))->assertOk()->assertDontSee($trip->trip_number);
        $this->assertDatabaseHas('trip_settlements', ['trip_id' => $trip->id, 'difference_amount' => 5000]);
        $this->get(route('trips.show', $trip))->assertOk()->assertSee('trip is CLOSED');
        $this->post(route('trips.expenses.store', $trip), [
            'category' => 'Fuel', 'amount' => 1, 'expense_date' => '2026-08-31',
        ])->assertStatus(422);
    }

    public function test_collection_can_include_an_expense_that_can_be_corrected_later(): void
    {
        $trip = Trip::create([
            'trip_number' => 'TR-2026-08-31-100', 'trip_date' => '2026-08-31',
            'deliveryman_name' => 'Bilal Raza', 'vehicle' => 'DEF-456', 'market_area' => 'North Nazimabad',
            'status' => 'COMPLETED', 'load_value' => 80000, 'expected_cash' => 60000,
        ]);

        $this->post(route('trips.collections.store', $trip), [
            'customer' => 'Test Store', 'invoice_number' => 'INV-100', 'amount' => 50000,
            'method' => 'Cash', 'collected_at' => '2026-08-31 15:00:00',
            'expense_category' => 'Fuel', 'expense_amount' => 2500,
            'expense_date' => '2026-08-31', 'expense_description' => 'Estimated fuel cost',
        ])->assertRedirect(route('trips.show', $trip));

        $expense = $trip->expenses()->firstOrFail();
        $this->assertDatabaseHas('trip_expenses', ['id' => $expense->id, 'amount' => 2500]);

        $this->put(route('trips.expenses.update', [$trip, $expense]), [
            'category' => 'Fuel', 'amount' => 2200, 'expense_date' => '2026-08-31',
            'description' => 'Corrected from receipt',
        ])->assertRedirect();

        $this->assertDatabaseHas('trip_expenses', ['id' => $expense->id, 'amount' => 2200, 'description' => 'Corrected from receipt']);
    }
}

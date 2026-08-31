<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('trip_number')->unique();
            $table->date('trip_date');
            $table->unsignedBigInteger('deliveryman_id')->nullable();
            $table->string('deliveryman_name');
            $table->string('vehicle');
            $table->string('market_area');
            $table->string('source_dlf')->nullable();
            $table->string('status')->default('DRAFT');
            $table->decimal('load_value', 15, 2)->default(0);
            $table->decimal('expected_cash', 15, 2)->default(0);
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('trip_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('collection_ref')->unique();
            $table->string('customer');
            $table->string('invoice_number');
            $table->decimal('amount', 15, 2);
            $table->string('method');
            $table->string('cheque_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->date('instrument_date')->nullable();
            $table->string('bank_reference')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('collected_at');
            $table->timestamps();
        });

        Schema::create('trip_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('expense_ref')->unique();
            $table->string('category');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->timestamps();
        });

        Schema::create('trip_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('expected_cash', 15, 2);
            $table->decimal('collected_amount', 15, 2);
            $table->decimal('expense_amount', 15, 2);
            $table->decimal('difference_amount', 15, 2);
            $table->string('shortage_classification')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('settled_at');
            $table->timestamps();
        });

        $now = now();
        DB::table('trips')->insert([
            ['trip_number' => 'TR-2026-08-31-001', 'trip_date' => '2026-08-31', 'deliveryman_id' => 1, 'deliveryman_name' => 'Ahmed Khan', 'vehicle' => 'Toyota Hilux - ABC-123', 'market_area' => 'Gulshan-e-Iqbal', 'source_dlf' => 'DLF-10245', 'status' => 'DISPATCHED', 'load_value' => 350000, 'expected_cash' => 200000, 'created_at' => $now, 'updated_at' => $now],
            ['trip_number' => 'TR-2026-08-31-002', 'trip_date' => '2026-08-31', 'deliveryman_id' => 2, 'deliveryman_name' => 'Bilal Raza', 'vehicle' => 'Suzuki Ravi - DEF-456', 'market_area' => 'North Nazimabad', 'source_dlf' => 'DLF-10246', 'status' => 'DRAFT', 'load_value' => 98000, 'expected_cash' => 76000, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_settlements');
        Schema::dropIfExists('trip_expenses');
        Schema::dropIfExists('trip_collections');
        Schema::dropIfExists('trips');
    }
};

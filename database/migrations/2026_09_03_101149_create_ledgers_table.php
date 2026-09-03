<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('ledger_group', 20);
            $table->string('entity_name');
            $table->foreignId('deliveryman_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('entry_date');
            $table->string('voucher_reference');
            $table->string('transaction_category');
            $table->enum('entry_type', ['Debit', 'Credit']);
            $table->decimal('amount', 14, 2);
            $table->decimal('previous_balance', 14, 2)->default(0);
            $table->decimal('running_balance', 14, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('bank_reference')->nullable();
            $table->string('linked_invoice_trip')->nullable();
            $table->text('remarks')->nullable();
            $table->string('document_path')->nullable();
            $table->string('verification_status')->default('Pending Verification');
            $table->string('created_by')->default('Admin');
            $table->timestamps();
            $table->index(['ledger_group', 'entity_name', 'entry_date']);
            $table->index(['transaction_category', 'entry_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};

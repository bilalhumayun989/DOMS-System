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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_id')->unique();
            $table->date('date');
            $table->string('category');
            $table->string('source');
            $table->string('driver')->nullable();
            $table->string('route')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('voucher')->nullable();
            $table->string('status');
            $table->string('approved_by')->nullable();
            $table->string('payment_source')->nullable();
            $table->string('receipt')->nullable();
            $table->string('market')->nullable();
            $table->string('created_by');
            $table->text('notes')->nullable();
            $table->foreignId('trip_id')->nullable()->constrained('trips')->restrictOnDelete();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

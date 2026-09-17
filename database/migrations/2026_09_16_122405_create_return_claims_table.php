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
        Schema::create('return_claims', function (Blueprint $table) {
            $table->id();
            $table->string('return_ref')->nullable();
            $table->string('trip_display')->nullable();
            $table->string('invoice_ref')->nullable();
            $table->string('shop')->nullable();
            $table->string('market')->nullable();
            $table->string('deliveryman')->nullable();
            $table->string('distributor')->nullable();
            $table->string('return_type')->nullable();
            $table->string('units')->nullable();
            $table->string('status')->nullable();
            $table->string('main_reason')->nullable();
            $table->string('condition')->nullable();
            $table->string('credit_note')->nullable();
            $table->string('impact')->nullable();
            $table->string('claim_status')->nullable();
            $table->foreignId('trip_id')->nullable()->constrained('trips')->restrictOnDelete();
            $table->date('date');
            $table->decimal('value', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->json('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_claims');
    }
};

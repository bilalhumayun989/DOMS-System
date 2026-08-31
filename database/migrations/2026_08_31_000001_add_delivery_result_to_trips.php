<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('delivery_result')->nullable()->after('status');
            $table->date('follow_up_date')->nullable()->after('delivery_result');
            $table->text('delivery_notes')->nullable()->after('follow_up_date');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['delivery_result', 'follow_up_date', 'delivery_notes']);
        });
    }
};

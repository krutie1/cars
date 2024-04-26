<?php

use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            Payment::create([
                'name' => 'Наличные'
            ]);
            Payment::create([
                'name' => 'Halyq-QR'
            ]);
            Payment::create([
                'name' => 'Jusan terminal'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            Payment::query()->delete();
        });
    }
};

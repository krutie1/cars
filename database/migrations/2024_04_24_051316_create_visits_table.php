<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->string('comment');
            $table->decimal('cost', 10, 0)->default(0);
            $table->decimal('discount', 10, 0)->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('payment_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};

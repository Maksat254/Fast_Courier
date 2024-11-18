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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_id')->nullable()->constrained('couriers')->onDelete('set null');
            $table->boolean('courier_accepted')->default(false);
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('delivery_address');
            $table->string('pickup_address');
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 8, 2);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

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
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->string('delivery_address');
            $table->text('delivery_description')->nullable();
            $table->decimal('product_amount', 8, 2);
            $table->decimal('delivery_amount', 8, 2);
            $table->decimal('fee_amount', 8, 2);
            $table->string('status')->default(\App\Enums\OrderStatus::NEW->value);
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

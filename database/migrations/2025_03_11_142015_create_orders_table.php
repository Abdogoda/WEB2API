<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->string('order_number')->unique();
            $table->string('shipping_address');
            $table->string('shipping_country');
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('orders');
    }
};

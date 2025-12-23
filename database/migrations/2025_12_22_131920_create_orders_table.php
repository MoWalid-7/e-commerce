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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->decimal('subtotal',10,2)->default(000000);
            $table->decimal('tax',10,2)->default(0);
            $table->decimal('discout',10,2)->default(0);
            $table->enum('payment_method',['card','vodafone_cash','cash_on_delivery'])->default('cash_on_delivery');
            $table->decimal('total',10,2);
            $table->text('address');
            $table->enum('status',[
                'pending',
                'paid',
                'processing',
                'shipped',
                'completed',
                'cancelled'
            ])->default('pending');
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
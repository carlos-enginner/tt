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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('consumer_name');
            $table->bigInteger('consumer_id');
            $table->text('purchase_description');
            $table->double('purchase_value');
            $table->string('payment_method');
            $table->string('payment_metadata')->nullable();
            $table->text('consumer_transaction_metadata');
            $table->text('charge_transaction_metadata');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

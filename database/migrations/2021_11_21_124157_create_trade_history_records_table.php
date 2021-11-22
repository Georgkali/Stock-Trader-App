<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeHistoryRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_history_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->text('company');
            $table->text('buy/sell');
            $table->float('stock_purchase_price')->nullable();
            $table->float('stock_selling_price')->nullable();
            $table->float('total_purchase_price')->nullable();
            $table->float('total_selling_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade_history_records');
    }
}

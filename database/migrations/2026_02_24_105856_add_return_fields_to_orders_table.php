<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('orders', function (Blueprint $table) {

        $table->text('remark')->nullable()->after('order_status');
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamp('return_requested_at')->nullable();
        $table->timestamp('returned_at')->nullable();

    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {

        $table->dropColumn([
            'remark',
            'cancelled_at',
            'return_requested_at',
            'returned_at'
        ]);

    });
}
};

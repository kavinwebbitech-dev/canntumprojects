<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->unsignedInteger('product_detail_id')->nullable()->after('product_id');
            $table->foreign('product_detail_id')
                  ->references('id')
                  ->on('product_details')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropForeign(['product_detail_id']);
            $table->dropColumn('product_detail_id');
        });
    }
};

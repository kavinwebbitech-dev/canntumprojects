<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('coupons', function (Blueprint $table) {
        $table->integer('user_limit')->default(1);
        $table->date('expiry_date')->nullable();
    });
}

public function down()
{
    Schema::table('coupons', function (Blueprint $table) {
        $table->dropColumn(['user_limit', 'expiry_date']);
    });
}

};

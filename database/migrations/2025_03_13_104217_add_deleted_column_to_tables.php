<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tables = ['customers', 'stages', 'suppliers', 'categories', 'products', 'warehouses', 'payment_modes'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('deleted')->default('no')->after('id'); // Adjust position if needed
            });
        }
    }

    public function down()
    {
        $tables = ['customers', 'stages', 'suppliers', 'categories', 'products', 'warehouses', 'payment_modes'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
    }
};

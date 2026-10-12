<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Encrypted values are much longer than the plain text they replace,
 * so these columns need to be widened before we start encrypting them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('customer_name')->change();
            $table->text('contact_info')->change();
            $table->text('file_original_name')->change();
        });

        Schema::table('archived_orders', function (Blueprint $table) {
            $table->text('customer_name')->change();
            $table->text('contact_info')->change();
            $table->text('file_original_name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->change();
            $table->string('contact_info')->change();
            $table->string('file_original_name')->change();
        });

        Schema::table('archived_orders', function (Blueprint $table) {
            $table->string('customer_name')->change();
            $table->string('contact_info')->change();
            $table->string('file_original_name')->change();
        });
    }
};

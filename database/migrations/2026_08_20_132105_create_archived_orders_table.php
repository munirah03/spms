<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archived_orders', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('order_id');
            $table->string('customer_name');
            $table->string('contact_info');
            $table->string('file_original_name');
            $table->string('file_type');
            $table->unsignedInteger('page_count')->default(1);
            $table->unsignedInteger('copies')->default(1);
            $table->string('color_mode');
            $table->string('paper_size');
            $table->string('orientation');
            $table->string('payment_method');
            $table->decimal('estimated_price', 8, 2)->default(0);
            $table->boolean('binding')->default(false);
            $table->boolean('plastic_cover')->default(false);
            $table->string('status');
            $table->timestamp('original_created_at')->nullable();
            $table->timestamps();

            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archived_orders');
    }
};
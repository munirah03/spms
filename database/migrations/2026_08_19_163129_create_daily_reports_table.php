<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->unique();
            $table->unsignedInteger('total_orders')->default(0);
            $table->unsignedInteger('pending_count')->default(0);
            $table->unsignedInteger('printing_count')->default(0);
            $table->unsignedInteger('completed_count')->default(0);
            $table->unsignedInteger('collected_count')->default(0);
            $table->decimal('total_estimated_revenue', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('color_mode', ['color', 'black_white'])->default('black_white')->after('file_size');
            $table->enum('paper_size', ['A4', 'A3', 'Letter', 'Legal'])->default('A4')->after('color_mode');
            $table->enum('orientation', ['portrait', 'landscape'])->default('portrait')->after('paper_size');
            $table->text('special_request')->nullable()->after('orientation');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['color_mode', 'paper_size', 'orientation', 'special_request']);
        });
    }
};
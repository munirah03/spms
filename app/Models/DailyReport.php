<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'report_date',
        'total_orders',
        'pending_count',
        'printing_count',
        'completed_count',
        'collected_count',
        'total_estimated_revenue',
    ];
}
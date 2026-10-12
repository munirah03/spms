<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivedOrder extends Model
{
    protected $fillable = [
        'report_date', 'order_id', 'customer_name', 'contact_info',
        'file_original_name', 'file_type', 'page_count', 'copies',
        'color_mode', 'paper_size', 'orientation', 'payment_method',
        'estimated_price', 'binding', 'plastic_cover', 'status',
        'original_created_at',
    ];

    protected $casts = [
        'binding' => 'boolean',
        'plastic_cover' => 'boolean',
        // PII encrypted at rest — plaintext never touches the database.
        'customer_name' => 'encrypted',
        'contact_info' => 'encrypted',
        'file_original_name' => 'encrypted',
    ];
}
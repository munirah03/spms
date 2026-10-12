<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'customer_name',
        'contact_info',
        'file_original_name',
        'file_stored_name',
        'file_type',
        'file_size',
        'page_count',
        'copies',
        'color_mode',
        'paper_size',
        'orientation',
        'special_request',
        'payment_method',
        'estimated_price',
        'binding',
        'plastic_cover',
        'status',
    ];

    protected $casts = [
        'binding' => 'boolean',
        'plastic_cover' => 'boolean',
        // PII encrypted at rest — plaintext never touches the database.
        'customer_name' => 'encrypted',
        'contact_info' => 'encrypted',
        'file_original_name' => 'encrypted',
    ];

    /**
     * Delete every stored copy of this order's upload: the file shown to staff
     * and, for DOCX orders, the original document as well as its PDF conversion.
     */
    public function deleteStoredFiles(): void
    {
        $base = pathinfo((string) $this->file_stored_name, PATHINFO_FILENAME);

        if ($base === '') {
            return;
        }

        foreach (['pdf', 'docx', 'jpg', 'jpeg', 'png'] as $ext) {
            Storage::delete('private_uploads/' . $base . '.' . $ext);
        }
    }
}

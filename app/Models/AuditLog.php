<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'user_name', 'action', 'order_id', 'detail', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    /**
     * Record who did what, and when. Never throws: logging must not break the request.
     */
    public static function record(string $action, ?string $orderId = null, ?string $detail = null): void
    {
        try {
            $user = auth()->user();

            static::create([
                'user_id'    => $user?->id,
                'user_name'  => $user?->name,
                'action'     => $action,
                'order_id'   => $orderId,
                'detail'     => $detail,
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 250, ''),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}

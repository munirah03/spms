<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderNotifier
{
    public function send(string $contact, string $orderId): void
    {
        $contact = trim($contact);

        try {
            if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
                $this->sendEmail($contact, $orderId);
            } elseif ($phone = $this->normalizePhone($contact)) {
                $this->sendWhatsApp($phone, $orderId);
            }
        } catch (\Throwable $e) {
            // Order tetap berjaya walaupun notifikasi gagal. Jangan log nombor/emel customer.
            Log::warning('Order notification failed', [
                'order_id' => $orderId,
                'error'    => get_class($e),
            ]);
        }
    }

    private function normalizePhone(string $input): ?string
    {
        $digits = preg_replace('/\D+/', '', $input);

        if (str_starts_with($digits, '0')) {
            $digits = '6' . $digits; // 012xxxxxxx -> 6012xxxxxxx
        }

        return preg_match('/^601\d{8,9}$/', $digits) ? $digits : null;
    }

        private function trackUrl(): string
    {
        $base = rtrim((string) config('services.notify.track_base'), '/');

        return $base . route('orders.track', [], false);
    }

    private function sendWhatsApp(string $phone, string $orderId): void
    {
        $cfg = config('services.whatsapp');

        if (! $cfg['token'] || ! $cfg['phone_id']) {
            return;
        }

        $response = Http::withToken($cfg['token'])
            ->timeout(8)
            ->post("https://graph.facebook.com/{$cfg['version']}/{$cfg['phone_id']}/messages", [
                'messaging_product' => 'whatsapp',
                'to'                => $phone,
                'type'              => 'template',
                'template'          => [
                    'name'       => $cfg['template'],
                    'language'   => ['code' => $cfg['language']],
                    'components' => [[
                        'type'       => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $orderId],
                            ['type' => 'text', 'text' => $this->trackUrl()],
                        ],
                    ]],
                ],
            ]);

        if ($response->failed()) {
            Log::warning('WhatsApp send failed', [
                'status' => $response->status(),
                'error'  => $response->json('error.message'),
            ]);
        }
    }

    private function sendEmail(string $email, string $orderId): void
    {
        $body = "Thank you for submitting an order at Snap Momen.\n\n"
              . "Your Order ID: {$orderId}\n"
              . 'Check status at: ' . $this->trackUrl() . "\n";

        Mail::raw($body, fn ($m) => $m->to($email)->subject("Your Order ID: {$orderId}"));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser;
use App\Services\OrderNotifier;

class OrderController extends Controller
{
    private array $prices = [
    'black_white' => [
        'A4' => [[100, 0.50], [500, 0.40], [1000, 0.35], [PHP_INT_MAX, 0.30]],
        'A3' => [[100, 1.00], [500, 0.80], [1000, 0.60], [PHP_INT_MAX, 0.40]],
    ],
    'color' => [
        'A4' => [[100, 2.00], [500, 1.80], [1000, 1.50], [PHP_INT_MAX, 1.10]],
        'A3' => [[100, 3.00], [500, 2.80], [1000, 2.60], [PHP_INT_MAX, 2.50]],
    ],
];

private function unitPrice(string $mode, string $size, int $totalPages): float
{
    $size = $size === 'A5' ? 'A4' : $size; // A4 & A5 harga sama

    foreach ($this->prices[$mode][$size] ?? [] as [$max, $price]) {
        if ($totalPages <= $max) {
            return $price;
        }
    }

    return 0;
}

    private array $extraServices = [
        'binding'       => 1.00,
        'plastic_cover' => 1.00,
    ];

    public function create(Request $request)
    {
        return view('orders.create', [
            'prefillName' => session('last_customer_name'),
            'prefillContact' => session('last_contact_info'),
            'prices' => $this->prices,
            'extraServices' => $this->extraServices,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'contact_info'    => 'required|string|max:255',
            'file'            => 'required|file|mimes:pdf,jpg,jpeg,png,docx|max:5120',
            'copies'          => 'required|integer|min:1|max:100',
            'color_mode'      => 'required|in:color,black_white',
            'paper_size'      => 'required|in:A4,A5,A3',
            'orientation'     => 'required|in:portrait,landscape',
            'special_request' => 'nullable|string|max:1000',
            'payment_method'  => 'required|string',
        ]);

        $file = $request->file('file');
        $originalExtension = strtolower($file->getClientOriginalExtension());

        if (! $this->hasValidContent($file, $originalExtension)) {
            throw ValidationException::withMessages([
                'file' => 'The uploaded file is not a valid ' . strtoupper($originalExtension) . ' document.',
            ]);
        }
        $storedName = Str::uuid() . '.' . $originalExtension;
        $file->storeAs('private_uploads', $storedName);

        // Convert DOCX to PDF so it can be previewed/printed like any other document
        $displayFileName = $storedName;
        $displayFileType = $originalExtension;

        if ($originalExtension === 'docx') {
            $uploadDir = storage_path('app/private_uploads');
            $sourcePath = $uploadDir . '/' . $storedName;
            $libreOfficePath = config('app.libreoffice_path');

            $command = '"' . $libreOfficePath . '" --headless --convert-to pdf --outdir "' . $uploadDir . '" "' . $sourcePath . '" 2>&1';
            exec($command);

            $convertedFileName = pathinfo($storedName, PATHINFO_FILENAME) . '.pdf';
            $convertedPath = $uploadDir . '/' . $convertedFileName;

            $attempts = 0;
            while (!file_exists($convertedPath) && $attempts < 10) {
                usleep(500000);
                $attempts++;
            }

            if (file_exists($convertedPath)) {
                $displayFileName = $convertedFileName;
                $displayFileType = 'pdf';
            }
        }

        $orderId = $this->generateOrderId($originalExtension);

        // Detect page count (PDFs only — images default to 1 page)
        $pageCount = 1;
        if ($displayFileType === 'pdf') {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile(storage_path('app/private_uploads/' . $displayFileName));
                $pageCount = count($pdf->getPages());
                if ($pageCount < 1) {
                    $pageCount = 1;
                }
            } catch (\Exception $e) {
                $pageCount = 1;
            }
        }

        $totalPages = $pageCount * (int) $request->copies;
        $pricePerCopyPerPage = $this->unitPrice($request->color_mode, $request->paper_size, $totalPages);
        $extrasTotal = 0;

        if ($request->has('binding')) {
            $extrasTotal += $this->extraServices['binding'];
        }
        if ($request->has('plastic_cover')) {
            $extrasTotal += $this->extraServices['plastic_cover'];
        }

        $estimatedPrice = round(($pricePerCopyPerPage * $pageCount * $request->copies) + $extrasTotal, 2);

        Order::create([
            'order_id'            => $orderId,
            'customer_name'       => $request->customer_name,
            'contact_info'        => $request->contact_info,
            'file_original_name'  => $file->getClientOriginalName(),
            'file_stored_name'    => $displayFileName,
            'file_type'           => $displayFileType,
            'file_size'           => $file->getSize(),
            'page_count'          => $pageCount,
            'copies'              => $request->copies,
            'color_mode'          => $request->color_mode,
            'paper_size'          => $request->paper_size,
            'orientation'         => $request->orientation,
            'special_request'     => $request->special_request,
            'payment_method'      => $request->payment_method,
            'estimated_price'     => $estimatedPrice,
            'binding'             => $request->has('binding'),
            'plastic_cover'       => $request->has('plastic_cover'),
            'status'              => 'pending',
        ]);

        session([
            'last_customer_name' => $request->customer_name,
            'last_contact_info'  => $request->contact_info,
        ]);

                session()->flash('last_order_id', $orderId);
        session()->flash('last_price', $estimatedPrice);
        session()->flash('last_payment', $request->payment_method);

        // Hantar Order ID melalui emel (ID yang sama dengan skrin)
        $contact = (string) $request->contact_info;
        dispatch(fn () => app(\App\Services\OrderNotifier::class)->send($contact, $orderId))->afterResponse();

        return redirect()->route('orders.success');
    }

    /**
     * Generate a hard-to-guess Order ID based on file type.
     * Format: {TYPE}-{8 random characters}, e.g. PDF-K7X2QM9A
     */
    /**
     * Generate a short, easy-to-read tracking code the customer can say over
     * the phone or type without confusion. 6 characters from a 30-symbol set
     * with the visually-ambiguous ones removed (0/O, 1/I/L).
     *
     * Orders are wiped every night (ArchiveAndResetOrders), so this only needs
     * to be unique among the handful of orders active on a single day —
     * 30^6 (~729 million) combinations is more than enough for that, and the
     * `/track` and `/order` routes are rate-limited so guessing isn't practical.
     */
    private function generateOrderId(string $extension): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // no 0/O, 1/I/L

        do {
            $orderId = collect(range(1, 6))
                ->map(fn () => $alphabet[random_int(0, strlen($alphabet) - 1)])
                ->implode('');
        } while (Order::where('order_id', $orderId)->exists());

        return $orderId;
    }

    /**
     * Check the real file content (magic bytes), not just the client-supplied extension.
     */
    private function hasValidContent(UploadedFile $file, string $ext): bool
    {
        $head = (string) file_get_contents($file->getRealPath(), false, null, 0, 8);

        switch ($ext) {
            case 'pdf':
                return str_starts_with($head, '%PDF');
            case 'jpg':
            case 'jpeg':
                return str_starts_with($head, "\xFF\xD8\xFF");
            case 'png':
                return str_starts_with($head, "\x89PNG");
            case 'docx':
                if (! str_starts_with($head, "PK\x03\x04")) {
                    return false;
                }
                if (class_exists(\ZipArchive::class)) {
                    $zip = new \ZipArchive();
                    if ($zip->open($file->getRealPath()) !== true) {
                        return false;
                    }
                    // A real Word document has word/document.xml and no macro project.
                    $ok = $zip->locateName('word/document.xml') !== false
                        && $zip->locateName('word/vbaProject.bin') === false;
                    $zip->close();

                    return $ok;
                }

                return true;
            default:
                return false;
        }
    }

    public function success()
    {
        $orderId = session('last_order_id');

        if (!$orderId) {
            return redirect()->route('orders.create');
        }

        return view('orders.success', [
            'orderId' => $orderId,
            'price' => session('last_price'),
            'payment' => session('last_payment'),
        ]);
    }

    public function track(Request $request)
    {
        $order = null;
        $searched = $request->has('order_id');

        if ($searched) {
            $orderId = strtoupper(str_replace(' ', '', trim((string) $request->order_id)));
            $order = Order::where('order_id', $orderId)->first();
        }

        return view('orders.track', ['order' => $order, 'searched' => $searched]);
    }
}
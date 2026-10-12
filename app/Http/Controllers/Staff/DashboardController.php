<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    /**
     * Show all orders
     */
    public function index(Request $request)
{
    $search = $request->input('search');

    if ($search) {
        // customer_name and contact_info are encrypted at rest, so a plain SQL
        // LIKE can't match them — filter those two in PHP after decryption.
        // order_id stays a normal DB search since it is not encrypted.
        $orders = Order::where('order_id', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->get()
            ->merge(
                Order::orderBy('created_at', 'desc')->get()->filter(function ($order) use ($search) {
                    return stripos($order->customer_name, $search) !== false
                        || stripos($order->contact_info, $search) !== false;
                })
            )
            ->unique('id')
            ->sortByDesc('created_at')
            ->values();
    } else {
        $orders = Order::orderBy('created_at', 'desc')->get();
    }

    return view('staff.dashboard', compact('orders', 'search'));
}

    /**
     * Show a single order's details
     */
    public function show(Order $order)
    {
        AuditLog::record('order_viewed', $order->order_id);

        return view('staff.show', compact('order'));
    }

    /**
     * Show the view-only preview page (no print/save)
     */
    public function preview(Order $order)
    {
        $path = storage_path('app/private_uploads/' . $order->file_stored_name);

        if (!file_exists($path)) {
            abort(404);
        }

        AuditLog::record('file_previewed', $order->order_id);

        return view('staff.preview', [
            'order' => $order,
            'fileUrl' => $this->signedFileUrl($order),
        ]);
    }

    /**
     * Raw file stream (used inside preview and print pages only)
     */
    public function file(Order $order)
    {
        $path = storage_path('app/private_uploads/' . $order->file_stored_name);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Disposition' => 'inline',
            'Cache-Control'       => 'no-store, private',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Show the print page (auto-triggers browser print dialog)
     */
    public function print(Order $order)
{
    $path = storage_path('app/private_uploads/' . $order->file_stored_name);

    if (!file_exists($path)) {
        abort(404);
    }

    AuditLog::record('file_printed', $order->order_id);

    return view('staff.print', [
        'order' => $order,
        'fileUrl' => $this->signedFileUrl($order),
    ]);
}

    /**
     * Update order status, auto-delete file if collected
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,printing,completed,collected',
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        $order->save();

        AuditLog::record('status_changed', $order->order_id, $oldStatus . ' -> ' . $order->status);

        if ($order->status === 'collected') {
            $order->deleteStoredFiles();
        }

        return redirect()->route('staff.dashboard')->with('success', 'Order updated successfully.');
    }


public function reports(Request $request)
{
    AuditLog::record('reports_viewed');

    $search = $request->input('search');

    $reports = \App\Models\DailyReport::when($search, function ($query, $search) {
            $query->whereDate('report_date', $search);
        })
        ->orderBy('report_date', 'desc')
        ->get();

    // Build a live "today" summary from current orders (not yet archived)
    $todayOrders = Order::all();
    $todayReport = (object) [
        'report_date' => now()->toDateString(),
        'total_orders' => $todayOrders->count(),
        'pending_count' => $todayOrders->where('status', 'pending')->count(),
        'printing_count' => $todayOrders->where('status', 'printing')->count(),
        'completed_count' => $todayOrders->where('status', 'completed')->count(),
        'collected_count' => $todayOrders->where('status', 'collected')->count(),
        'total_estimated_revenue' => $todayOrders->sum('estimated_price'),
        'is_live' => true,
    ];

    return view('staff.reports', compact('reports', 'search', 'todayReport'));
}

public function reportDetail($date)
{
    $report = \App\Models\DailyReport::where('report_date', $date)->firstOrFail();
    $orders = \App\Models\ArchivedOrder::where('report_date', $date)
        ->orderBy('original_created_at', 'asc')
        ->get();

    return view('staff.report-detail', compact('report', 'orders', 'date'));
}


    /**
     * Short-lived signed link so the raw file can only be opened from a page
     * a logged-in staff member just loaded (not shared or bookmarked).
     */
    private function signedFileUrl(Order $order): string
    {
        return URL::temporarySignedRoute('staff.file', now()->addMinutes(10), ['order' => $order->id]);
    }
}

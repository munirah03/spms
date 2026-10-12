<?php

namespace App\Console\Commands;

use App\Models\ArchivedOrder;
use App\Models\DailyReport;
use App\Models\Order;
use Illuminate\Console\Command;

class ArchiveAndResetOrders extends Command
{
    protected $signature = 'orders:archive-reset';
    protected $description = 'Archive today\'s order summary and details into reports, then clear all orders and files';

    public function handle()
    {
        $orders = Order::all();
        $today = now()->toDateString();

        DailyReport::updateOrCreate(
            ['report_date' => $today],
            [
                'total_orders'    => $orders->count(),
                'pending_count'   => $orders->where('status', 'pending')->count(),
                'printing_count'  => $orders->where('status', 'printing')->count(),
                'completed_count' => $orders->where('status', 'completed')->count(),
                'collected_count' => $orders->where('status', 'collected')->count(),
                'total_estimated_revenue' => $orders->sum('estimated_price'),
            ]
        );

        foreach ($orders as $order) {
            ArchivedOrder::create([
                'report_date'         => $today,
                'order_id'            => $order->order_id,
                'customer_name'       => $order->customer_name,
                'contact_info'        => $order->contact_info,
                'file_original_name'  => $order->file_original_name,
                'file_type'           => $order->file_type,
                'page_count'          => $order->page_count,
                'copies'              => $order->copies,
                'color_mode'          => $order->color_mode,
                'paper_size'          => $order->paper_size,
                'orientation'         => $order->orientation,
                'payment_method'      => $order->payment_method,
                'estimated_price'     => $order->estimated_price,
                'binding'             => $order->binding,
                'plastic_cover'       => $order->plastic_cover,
                'status'              => $order->status,
                'original_created_at' => $order->created_at,
            ]);

            $order->deleteStoredFiles();
        }

        Order::truncate();

        $this->info('Daily report and order details saved. Orders and files reset for the new day.');
    }
}
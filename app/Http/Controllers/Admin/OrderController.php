<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.order.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.order.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'nullable|string|max:255',
            'shipping_country' => 'required|string|max:255',
            'shipping_zip_code' => 'required|string|max:20',
            'billing_name' => 'nullable|string|max:255',
            'billing_email' => 'nullable|email|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:255',
            'billing_state' => 'nullable|string|max:255',
            'billing_country' => 'nullable|string|max:255',
            'billing_zip_code' => 'nullable|string|max:20',
            'tracking_number' => 'nullable|string|max:255',
            'tracking_url' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Update shipped_at timestamp when status changes to shipped
        if ($request->status === 'shipped' && $order->status !== 'shipped') {
            $data['shipped_at'] = now();
        }

        // Update paid_at timestamp when payment status changes to paid
        if ($request->payment_status === 'paid' && $order->payment_status !== 'paid') {
            $data['paid_at'] = now();
        }

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        // Delete associated order items first
        $order->orderItems()->delete();
        
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
        ]);

        $data = ['status' => $request->status];

        // Update shipped_at timestamp when status changes to shipped
        if ($request->status === 'shipped' && $order->status !== 'shipped') {
            $data['shipped_at'] = now();
        }

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order status updated successfully.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $data = ['payment_status' => $request->payment_status];

        // Update paid_at timestamp when payment status changes to paid
        if ($request->payment_status === 'paid' && $order->payment_status !== 'paid') {
            $data['paid_at'] = now();
        }

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Payment status updated successfully.');
    }

    public function addTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
            'tracking_url' => 'nullable|url|max:500',
        ]);

        $order->update([
            'tracking_number' => $request->tracking_number,
            'tracking_url' => $request->tracking_url,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Tracking information added successfully.');
    }

    public function export()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        // This is a basic CSV export - you might want to use a package like Laravel Excel
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Order Number',
                'Customer',
                'Email',
                'Total',
                'Status',
                'Payment Status',
                'Created Date'
            ]);

            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->shipping_name,
                    $order->shipping_email,
                    $order->formatted_total,
                    $order->status,
                    $order->payment_status,
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems'])->latest();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Payment status filter
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('amount_min')) {
            $query->where('total', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('total', '<=', $request->amount_max);
        }

        $orders = $query->paginate(20);

        // Get statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::pending()->count(),
            'processing_orders' => Order::processing()->count(),
            'shipped_orders' => Order::shipped()->count(),
            'delivered_orders' => Order::delivered()->count(),
            'paid_orders' => Order::paid()->count(),
            'unpaid_orders' => Order::unpaid()->count(),
            'today_orders' => Order::today()->count(),
            'this_week_orders' => Order::thisWeek()->count(),
            'this_month_orders' => Order::thisMonth()->count(),
            'total_revenue' => Order::paid()->sum('total'),
            'today_revenue' => Order::paid()->today()->sum('total'),
            'this_week_revenue' => Order::paid()->thisWeek()->sum('total'),
            'this_month_revenue' => Order::paid()->thisMonth()->sum('total'),
        ];

        return view('admin.order.index', compact('orders', 'stats'));
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
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

        // Update order
        $order->update($validated);

        // Send email notification if status changed
        if ($oldStatus !== $validated['status']) {
            try {
                Mail::to($order->shipping_email)->send(new OrderStatusUpdate($order, $oldStatus, $validated['status']));
                
                Log::info('Order status update email sent', [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                    'email' => $order->shipping_email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send order status update email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;

        // Validate status transitions
        if (!$this->canTransitionToStatus($order, $validated['status'])) {
            return back()->with('error', 'Invalid status transition.');
        }

        // Update status
        switch ($validated['status']) {
            case 'shipped':
                $order->markAsShipped();
                break;
            case 'delivered':
                $order->markAsDelivered();
                break;
            case 'cancelled':
                $order->markAsCancelled();
                break;
            default:
                $order->update(['status' => $validated['status']]);
        }

        // Send email notification
        if ($oldStatus !== $validated['status']) {
            try {
                Mail::to($order->shipping_email)->send(new OrderStatusUpdate($order, $oldStatus, $validated['status']));
                
                Log::info('Order status update email sent', [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                    'email' => $order->shipping_email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send order status update email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $oldPaymentStatus = $order->payment_status;

        $order->update([
            'payment_status' => $validated['payment_status'],
            'paid_at' => $validated['payment_status'] === 'paid' ? now() : null,
        ]);

        return back()->with('success', 'Payment status updated successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'action' => 'required|in:update_status,update_payment_status,delete',
            'status' => 'required_if:action,update_status|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required_if:action,update_payment_status|in:pending,paid,failed,refunded',
        ]);

        $orders = Order::whereIn('id', $validated['order_ids'])->get();
        $updatedCount = 0;

        foreach ($orders as $order) {
            try {
                switch ($validated['action']) {
                    case 'update_status':
                        if ($this->canTransitionToStatus($order, $validated['status'])) {
                            $oldStatus = $order->status;
                            
                            switch ($validated['status']) {
                                case 'shipped':
                                    $order->markAsShipped();
                                    break;
                                case 'delivered':
                                    $order->markAsDelivered();
                                    break;
                                case 'cancelled':
                                    $order->markAsCancelled();
                                    break;
                                default:
                                    $order->update(['status' => $validated['status']]);
                            }

                            // Send email notification
                            if ($oldStatus !== $validated['status']) {
                                try {
                                    Mail::to($order->shipping_email)->send(new OrderStatusUpdate($order, $oldStatus, $validated['status']));
                                } catch (\Exception $e) {
                                    Log::error('Failed to send bulk order status update email', [
                                        'order_id' => $order->id,
                                        'error' => $e->getMessage()
                                    ]);
                                }
                            }
                            
                            $updatedCount++;
                        }
                        break;

                    case 'update_payment_status':
                        $order->update([
                            'payment_status' => $validated['payment_status'],
                            'paid_at' => $validated['payment_status'] === 'paid' ? now() : null,
                        ]);
                        $updatedCount++;
                        break;

                    case 'delete':
                        $order->delete();
                        $updatedCount++;
                        break;
                }
            } catch (\Exception $e) {
                Log::error('Bulk order update error', [
                    'order_id' => $order->id,
                    'action' => $validated['action'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        $actionName = str_replace('_', ' ', $validated['action']);
        return back()->with('success', "Successfully {$actionName} {$updatedCount} order(s).");
    }

    public function export(Request $request)
    {
        $query = Order::with(['user', 'orderItems']);

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Payment Status',
                'Total',
                'Items Count',
                'Order Date',
                'Payment Date',
                'Shipping Address',
                'Notes'
            ]);

            // CSV data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    ucfirst($order->status),
                    ucfirst($order->payment_status),
                    $order->formatted_total,
                    $order->items_count,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->paid_at ? $order->paid_at->format('Y-m-d H:i:s') : '',
                    $order->shipping_address . ', ' . $order->shipping_city . ', ' . $order->shipping_state,
                    $order->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function analytics()
    {
        // Daily orders for the last 30 days
        $dailyOrders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly orders for the last 12 months
        $monthlyOrders = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(total) as revenue')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top products
        $topProducts = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(order_items.quantity) as total_quantity, SUM(order_items.total_price) as total_revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Order status distribution
        $statusDistribution = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Payment status distribution
        $paymentStatusDistribution = Order::selectRaw('payment_status, COUNT(*) as count')
            ->groupBy('payment_status')
            ->get();

        return view('admin.order.analytics', compact(
            'dailyOrders',
            'monthlyOrders',
            'topProducts',
            'statusDistribution',
            'paymentStatusDistribution'
        ));
    }

    private function canTransitionToStatus(Order $order, string $newStatus): bool
    {
        $allowedTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$order->status] ?? []);
    }
}

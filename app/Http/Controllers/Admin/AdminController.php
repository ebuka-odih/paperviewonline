<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $stats = [
            'revenue_total' => $this->revenueBetween(),
            'revenue_this_month' => $this->revenueBetween($now->copy()->startOfMonth()),
            'revenue_last_month' => $this->revenueBetween(
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth()
            ),
            'revenue_this_week' => $this->revenueBetween($now->copy()->startOfWeek()),
            'revenue_last_week' => $this->revenueBetween(
                $now->copy()->subWeek()->startOfWeek(),
                $now->copy()->subWeek()->endOfWeek()
            ),
            'orders_total' => Order::count(),
            'orders_this_month' => Order::where('created_at', '>=', $now->copy()->startOfMonth())->count(),
            'orders_last_month' => Order::whereBetween('created_at', [
                $now->copy()->subMonthNoOverflow()->startOfMonth(),
                $now->copy()->subMonthNoOverflow()->endOfMonth(),
            ])->count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'orders_processing' => Order::where('status', 'processing')->count(),
            'awaiting_payment' => Order::where('payment_status', 'pending')->count(),
            'customers_total' => User::where('role', '!=', 'admin')->count(),
            'customers_this_month' => User::where('role', '!=', 'admin')
                ->where('created_at', '>=', $now->copy()->startOfMonth())->count(),
            'products_total' => Product::count(),
            'products_inactive' => Product::where('is_active', false)->count(),
            'products_missing_images' => Product::doesntHave('images')->count(),
            'low_stock' => Product::whereBetween('stock', [1, 10])->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
        ];

        $paidOrders = Order::where('payment_status', 'paid')->count();
        $stats['average_order'] = $paidOrders > 0 ? $stats['revenue_total'] / $paidOrders : 0;

        return view('admin.index', [
            'stats' => $stats,
            'revenueTrend' => $this->percentChange($stats['revenue_this_month'], $stats['revenue_last_month']),
            'weekTrend' => $this->percentChange($stats['revenue_this_week'], $stats['revenue_last_week']),
            'orderTrend' => $this->percentChange($stats['orders_this_month'], $stats['orders_last_month']),
            'salesSeries' => $this->salesSeries(),
            'recentOrders' => Order::with('user')->latest()->limit(8)->get(),
            'topProducts' => $this->topProducts(),
            'lowStockProducts' => Product::with('category')
                ->where('stock', '<=', 10)
                ->orderBy('stock')
                ->limit(6)
                ->get(),
            'ordersByStatus' => Order::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    protected function revenueBetween(?Carbon $from = null, ?Carbon $to = null): float
    {
        return (float) Order::where('payment_status', 'paid')
            ->when($from, fn ($query) => $query->where('created_at', '>=', $from))
            ->when($to, fn ($query) => $query->where('created_at', '<=', $to))
            ->sum('total');
    }

    /**
     * Daily paid revenue for the last 14 days, zero-filled so the chart has no gaps.
     */
    protected function salesSeries(int $days = 14): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $totals = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $start)
            ->get()
            ->groupBy(fn ($order) => $order->created_at->toDateString())
            ->map(fn ($orders) => (float) $orders->sum('total'));

        $labels = [];
        $values = [];

        foreach (CarbonPeriod::create($start, Carbon::today()) as $day) {
            $labels[] = $day->format('M j');
            $values[] = round($totals->get($day->toDateString(), 0), 2);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    protected function topProducts(int $limit = 5)
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as units'), DB::raw('SUM(total_price) as revenue'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->with('product.category')
            ->get()
            ->filter(fn ($row) => $row->product !== null)
            ->values();
    }

    protected function percentChange(float $current, float $previous): ?float
    {
        if ($previous <= 0) {
            return $current > 0 ? null : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}

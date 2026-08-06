@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

@php
    $money = fn ($value) => '$' . number_format((float) $value, 2);
    $trend = function ($value) {
        if ($value === null) {
            return ['icon' => 'arrow-long-up', 'class' => 'text-success', 'text' => 'new'];
        }
        if ($value > 0) {
            return ['icon' => 'arrow-long-up', 'class' => 'text-success', 'text' => number_format($value, 1) . '%'];
        }
        if ($value < 0) {
            return ['icon' => 'arrow-long-down', 'class' => 'text-danger', 'text' => number_format(abs($value), 1) . '%'];
        }
        return ['icon' => 'minus', 'class' => 'text-soft', 'text' => 'no change'];
    };
    $revenue = $trend($revenueTrend);
    $week = $trend($weekTrend);
    $orders = $trend($orderTrend);
@endphp

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-2">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Dashboard</h3>
                            <p class="text-soft mb-0">A live view of your store — {{ now()->format('l, j F Y') }}</p>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <em class="icon ni ni-plus"></em><span>Add product</span>
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light">
                                    <em class="icon ni ni-bag"></em><span>Orders</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Things that need the admin's attention right now. --}}
                @php
                    $alerts = collect([
                        $stats['orders_pending'] > 0 ? [
                            'icon' => 'clock', 'class' => 'warning',
                            'text' => $stats['orders_pending'] . ' ' . Str::plural('order', $stats['orders_pending']) . ' waiting to be processed',
                            'url' => route('admin.orders.index') . '?status=pending', 'cta' => 'Review',
                        ] : null,
                        $stats['out_of_stock'] > 0 ? [
                            'icon' => 'alert-circle', 'class' => 'danger',
                            'text' => $stats['out_of_stock'] . ' ' . Str::plural('product', $stats['out_of_stock']) . ' out of stock',
                            'url' => route('admin.products.index', ['stock' => 'out']), 'cta' => 'Restock',
                        ] : null,
                        $stats['products_missing_images'] > 0 ? [
                            'icon' => 'img', 'class' => 'info',
                            'text' => $stats['products_missing_images'] . ' ' . Str::plural('product', $stats['products_missing_images']) . ' have no photos',
                            'url' => route('admin.products.index'), 'cta' => 'Add photos',
                        ] : null,
                    ])->filter();
                @endphp

                @if($alerts->isNotEmpty())
                    <div class="nk-block nk-block-sm">
                        <div class="row g-3">
                            @foreach($alerts as $alert)
                                <div class="col-md-4">
                                    <a href="{{ $alert['url'] }}" class="card card-bordered h-100 text-decoration-none">
                                        <div class="card-inner d-flex align-items-center gap-3 py-3">
                                            <em class="icon ni ni-{{ $alert['icon'] }} text-{{ $alert['class'] }} fs-4"></em>
                                            <div class="flex-grow-1">
                                                <div class="fw-medium text-dark small">{{ $alert['text'] }}</div>
                                                <div class="text-soft" style="font-size:.75rem">{{ $alert['cta'] }} &rarr;</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="nk-block">
                    <div class="row g-gs">
                        {{-- Revenue --}}
                        <div class="col-xxl-4 col-md-6">
                            <div class="card is-dark h-100">
                                <div class="nk-ecwg nk-ecwg1">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title"><h6 class="title">Paid revenue</h6></div>
                                            <div class="card-tools">
                                                <a href="{{ route('admin.orders.analytics') }}" class="link">View report</a>
                                            </div>
                                        </div>
                                        <div class="data">
                                            <div class="amount">{{ $money($stats['revenue_total']) }}</div>
                                            <div class="info"><strong>{{ $money($stats['revenue_this_month']) }}</strong> this month</div>
                                        </div>
                                        <div class="data">
                                            <h6 class="sub-title">This week so far</h6>
                                            <div class="data-group">
                                                <div class="amount">{{ $money($stats['revenue_this_week']) }}</div>
                                                <div class="info text-end">
                                                    <span class="change {{ $week['class'] }}">
                                                        <em class="icon ni ni-{{ $week['icon'] }}"></em>{{ $week['text'] }}
                                                    </span><br><span>vs. last week</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                        <div class="nk-ecwg1-ck">
                                            <canvas id="pvRevenueChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Orders --}}
                        <div class="col-xxl-4 col-md-6">
                            <div class="card h-100">
                                <div class="card-inner">
                                    <div class="card-title-group mb-2">
                                        <div class="card-title"><h6 class="title">Orders</h6></div>
                                        <div class="card-tools">
                                            <a href="{{ route('admin.orders.index') }}" class="link link-sm">All orders</a>
                                        </div>
                                    </div>
                                    <div class="pv-stat-value">{{ number_format($stats['orders_total']) }}</div>
                                    <div class="pv-stat-label mb-3">
                                        {{ number_format($stats['orders_this_month']) }} this month
                                        <span class="change {{ $orders['class'] }} ms-1">
                                            <em class="icon ni ni-{{ $orders['icon'] }}"></em>{{ $orders['text'] }}
                                        </span>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach(['pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary', 'delivered' => 'success', 'cancelled' => 'danger'] as $status => $colour)
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                                                <a href="{{ route('admin.orders.index') }}?status={{ $status }}" class="text-soft text-capitalize small text-decoration-none">
                                                    <span class="badge bg-{{ $colour }} me-2" style="width:.5rem;height:.5rem;padding:0;border-radius:50%;display:inline-block"></span>{{ $status }}
                                                </a>
                                                <span class="fw-medium small">{{ number_format($ordersByStatus[$status] ?? 0) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Catalogue + customers --}}
                        <div class="col-xxl-4">
                            <div class="row g-gs h-100">
                                @php
                                    $tiles = [
                                        ['label' => 'Average order', 'value' => $money($stats['average_order']), 'hint' => 'across paid orders', 'icon' => 'coins', 'url' => route('admin.orders.analytics')],
                                        ['label' => 'Customers', 'value' => number_format($stats['customers_total']), 'hint' => '+' . number_format($stats['customers_this_month']) . ' this month', 'icon' => 'users', 'url' => null],
                                        ['label' => 'Products', 'value' => number_format($stats['products_total']), 'hint' => $stats['products_inactive'] . ' inactive', 'icon' => 'package', 'url' => route('admin.products.index')],
                                        ['label' => 'Low stock', 'value' => number_format($stats['low_stock']), 'hint' => '10 or fewer left', 'icon' => 'alert', 'url' => route('admin.products.index', ['stock' => 'low'])],
                                    ];
                                @endphp
                                @foreach($tiles as $tile)
                                    <div class="col-6">
                                        <div class="card card-bordered h-100 pv-stat-card">
                                            <div class="card-inner py-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="pv-stat-label">{{ $tile['label'] }}</div>
                                                        <div class="pv-stat-value">{{ $tile['value'] }}</div>
                                                        <div class="text-soft" style="font-size:.75rem">{{ $tile['hint'] }}</div>
                                                    </div>
                                                    <em class="icon ni ni-{{ $tile['icon'] }} text-soft"></em>
                                                </div>
                                                @if($tile['url'])
                                                    <a href="{{ $tile['url'] }}" class="stretched-link" aria-label="{{ $tile['label'] }}"></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Recent orders --}}
                        <div class="col-lg-7">
                            <div class="card card-bordered h-100">
                                <div class="card-inner border-bottom">
                                    <div class="card-title-group">
                                        <div class="card-title"><h6 class="title mb-0">Recent orders</h6></div>
                                        <div class="card-tools"><a href="{{ route('admin.orders.index') }}" class="link link-sm">View all</a></div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="small">Order</th>
                                                <th class="small">Customer</th>
                                                <th class="small">Status</th>
                                                <th class="small text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentOrders as $order)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.orders.index') }}?search={{ $order->order_number }}" class="fw-medium small">
                                                            #{{ $order->order_number ?? Str::limit($order->id, 8, '') }}
                                                        </a>
                                                        <div class="text-soft" style="font-size:.75rem">{{ $order->created_at->diffForHumans() }}</div>
                                                    </td>
                                                    <td class="small">{{ $order->shipping_name ?? $order->user->name ?? 'Guest' }}</td>
                                                    <td>
                                                        <span class="badge bg-outline-{{ ['pending' => 'warning', 'processing' => 'info', 'shipped' => 'primary', 'delivered' => 'success', 'cancelled' => 'danger'][$order->status] ?? 'secondary' }} text-capitalize">
                                                            {{ $order->status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end small fw-medium">{{ $money($order->total) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-soft py-4">No orders yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Best sellers + low stock --}}
                        <div class="col-lg-5">
                            <div class="card card-bordered mb-3">
                                <div class="card-inner border-bottom">
                                    <h6 class="title mb-0">Best sellers</h6>
                                </div>
                                <ul class="list-unstyled mb-0">
                                    @forelse($topProducts as $row)
                                        <li class="pv-list-row">
                                            <img src="{{ $row->product->featured_image_url ?? $row->product->images->first()?->url ?? asset('assets/images/product/placeholder.svg') }}"
                                                 alt="" class="pv-thumb">
                                            <div class="pv-list-main">
                                                <a href="{{ route('admin.products.edit', $row->product) }}" class="d-block text-truncate fw-medium small">{{ $row->product->name }}</a>
                                                <span class="text-soft" style="font-size:.75rem">{{ number_format($row->units) }} sold</span>
                                            </div>
                                            <span class="fw-medium small text-nowrap">{{ $money($row->revenue) }}</span>
                                        </li>
                                    @empty
                                        <li class="px-4 py-4 text-center text-soft small">No sales recorded yet.</li>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="card card-bordered">
                                <div class="card-inner border-bottom">
                                    <div class="card-title-group">
                                        <h6 class="title mb-0">Needs restocking</h6>
                                        <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="link link-sm">View all</a>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-0">
                                    @forelse($lowStockProducts as $product)
                                        <li class="pv-list-row">
                                            <div class="pv-list-main">
                                                <a href="{{ route('admin.products.edit', $product) }}" class="d-block text-truncate fw-medium small">{{ $product->name }}</a>
                                                <span class="text-soft" style="font-size:.75rem">{{ $product->category->name ?? 'Uncategorised' }}</span>
                                            </div>
                                            <span class="badge bg-{{ $product->stock <= 0 ? 'danger' : 'warning' }} text-nowrap">{{ $product->stock }} left</span>
                                        </li>
                                    @empty
                                        <li class="px-4 py-4 text-center text-soft small">Every product is well stocked.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        var canvas = document.getElementById('pvRevenueChart');
        if (!canvas || typeof Chart === 'undefined') return;

        var labels = @json($salesSeries['labels']);
        var values = @json($salesSeries['values']);

        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: values,
                    tension: 0.3,
                    borderWidth: 2,
                    borderColor: '#8ea6ff',
                    backgroundColor: 'rgba(142, 166, 255, 0.25)',
                    pointRadius: 3,
                    pointBorderColor: 'transparent',
                    pointBackgroundColor: 'transparent',
                    pointHoverRadius: 4,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#8ea6ff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: { display: false },
                tooltips: {
                    backgroundColor: '#1c2b46',
                    displayColors: false,
                    titleFontSize: 10,
                    bodyFontSize: 11,
                    callbacks: {
                        label: function (item) { return '$' + Number(item.yLabel).toFixed(2); }
                    }
                },
                scales: {
                    yAxes: [{ display: false, ticks: { beginAtZero: true } }],
                    xAxes: [{ display: false, gridLines: { color: 'transparent' } }]
                }
            }
        });
    })();
</script>
@endpush

@endsection

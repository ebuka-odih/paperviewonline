@extends('admin.layout.app')
@section('title', 'Products')
@section('content')

@php
    $hasFilters = collect($filters)->except('sort')->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty();
@endphp

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-2">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Products</h3>
                            <div class="nk-block-des text-soft">
                                <p class="mb-0">
                                    {{ number_format($stats['total']) }} total &middot;
                                    {{ number_format($stats['active']) }} active &middot;
                                    <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="text-warning">{{ number_format($stats['low_stock']) }} low stock</a> &middot;
                                    <a href="{{ route('admin.products.index', ['stock' => 'out']) }}" class="text-danger">{{ number_format($stats['out_of_stock']) }} out of stock</a>
                                </p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                <em class="icon ni ni-plus"></em><span>Add product</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    {{-- Filters --}}
                    <div class="card card-bordered mb-3">
                        <div class="card-inner py-3">
                            <form method="GET" action="{{ route('admin.products.index') }}" class="pv-filter-bar" id="filterForm">
                                <div class="form-control-wrap pv-search">
                                    <div class="form-icon form-icon-left"><em class="icon ni ni-search"></em></div>
                                    <input type="search" name="search" class="form-control"
                                           placeholder="Search by name, SKU or barcode"
                                           value="{{ $filters['search'] }}">
                                </div>

                                <select name="category" class="form-select" onchange="this.form.submit()">
                                    <option value="">All categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $filters['category'] === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Any status</option>
                                    <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $filters['status'] === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="featured" {{ $filters['status'] === 'featured' ? 'selected' : '' }}>Featured</option>
                                </select>

                                <select name="stock" class="form-select" onchange="this.form.submit()">
                                    <option value="">Any stock</option>
                                    <option value="in" {{ $filters['stock'] === 'in' ? 'selected' : '' }}>In stock</option>
                                    <option value="low" {{ $filters['stock'] === 'low' ? 'selected' : '' }}>Low stock (1–10)</option>
                                    <option value="out" {{ $filters['stock'] === 'out' ? 'selected' : '' }}>Out of stock</option>
                                </select>

                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="newest" {{ $filters['sort'] === 'newest' ? 'selected' : '' }}>Newest first</option>
                                    <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>Oldest first</option>
                                    <option value="name" {{ $filters['sort'] === 'name' ? 'selected' : '' }}>Name A–Z</option>
                                    <option value="price_high" {{ $filters['sort'] === 'price_high' ? 'selected' : '' }}>Price high–low</option>
                                    <option value="price_low" {{ $filters['sort'] === 'price_low' ? 'selected' : '' }}>Price low–high</option>
                                    <option value="stock_low" {{ $filters['sort'] === 'stock_low' ? 'selected' : '' }}>Stock low–high</option>
                                </select>

                                <button type="submit" class="btn btn-outline-primary">Apply</button>

                                @if($hasFilters)
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light">Clear</a>
                                @endif
                            </form>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="card card-bordered">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:64px"></th>
                                        <th>Product</th>
                                        <th class="d-none d-md-table-cell">Category</th>
                                        <th class="d-none d-lg-table-cell">Variants</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end" style="width:120px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        @php
                                            $cover = $product->images->firstWhere('is_featured', true) ?? $product->images->first();
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product) }}">
                                                    <img src="{{ $cover?->url ?? asset('assets/images/product/placeholder.svg') }}"
                                                         alt="" class="pv-thumb" loading="lazy">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product) }}" class="fw-medium d-block">
                                                    {{ $product->name }}
                                                </a>
                                                <span class="text-soft" style="font-size:.75rem">
                                                    {{ $product->sku }}
                                                    @if($product->images->isEmpty())
                                                        <span class="text-warning ms-1">
                                                            <em class="icon ni ni-alert-circle"></em> no photo
                                                        </span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <span class="text-soft small">{{ $product->category->name ?? 'Uncategorised' }}</span>
                                            </td>
                                            <td class="d-none d-lg-table-cell">
                                                @if($product->variants_count > 0)
                                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                                        @foreach($product->variants->whereNotNull('color')->unique('color')->take(5) as $variant)
                                                            <span class="pv-chip-dot d-inline-block"
                                                                  title="{{ $variant->color }}"
                                                                  style="background: {{ $variant->color_hex ?? '#dbdfea' }}"></span>
                                                        @endforeach
                                                        <span class="text-soft ms-1" style="font-size:.75rem">
                                                            {{ $product->variants_count }} {{ Str::plural('variant', $product->variants_count) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-soft" style="font-size:.75rem">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($product->is_on_sale)
                                                    <span class="fw-medium">${{ number_format($product->sale_price, 2) }}</span>
                                                    <s class="text-soft d-block" style="font-size:.75rem">${{ number_format($product->price, 2) }}</s>
                                                @else
                                                    <span class="fw-medium">${{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $stockClass = match ($product->stock_status) {
                                                        'out_of_stock' => 'danger',
                                                        'low_stock' => 'warning',
                                                        default => 'success',
                                                    };
                                                @endphp
                                                <span class="badge bg-outline-{{ $stockClass }}">{{ $product->stock }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="pv-status-toggle"
                                                            title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-end">
                                                <div class="pv-actions">
                                                    <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                                class="btn btn-sm btn-icon btn-outline-light"
                                                                title="{{ $product->is_featured ? 'Remove from featured' : 'Mark as featured' }}">
                                                            <em class="icon ni ni-star{{ $product->is_featured ? '-fill text-warning' : '' }}"></em>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.products.edit', $product) }}"
                                                       class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                                        <em class="icon ni ni-edit"></em>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-product-btn"
                                                            title="Delete"
                                                            data-action="{{ route('admin.products.destroy', $product) }}"
                                                            data-name="{{ $product->name }}"
                                                            data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                                                        <em class="icon ni ni-trash"></em>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <div class="text-center py-5">
                                                    <em class="icon ni ni-package text-soft" style="font-size:2.5rem"></em>
                                                    @if($hasFilters)
                                                        <p class="mt-2 mb-3 text-soft">No products match these filters.</p>
                                                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light btn-sm">Clear filters</a>
                                                    @else
                                                        <p class="mt-2 mb-3 text-soft">No products yet.</p>
                                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                                            <em class="icon ni ni-plus"></em><span>Add your first product</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($products->hasPages())
                            <div class="card-inner border-top">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Delete confirmation --}}
                <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="deleteProductForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="mb-0">
                                        Delete <strong id="deleteProductName"></strong>? Its photos and variants
                                        will be removed too. This cannot be undone.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.delete-product-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('deleteProductForm').action = button.dataset.action;
            document.getElementById('deleteProductName').textContent = button.dataset.name;
        });
    });
</script>
@endpush

@endsection

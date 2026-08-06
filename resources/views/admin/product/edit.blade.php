@extends('admin.layout.app')
@section('title', $product->name)
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-2">
                        <div class="nk-block-head-content">
                            <nav>
                                <ul class="breadcrumb breadcrumb-arrow mb-1">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                                    <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
                                </ul>
                            </nav>
                            <h3 class="nk-block-title page-title">{{ $product->name }}</h3>
                            <div class="nk-block-des text-soft">
                                <p class="mb-0">
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($product->is_featured)
                                        <span class="badge bg-info">Featured</span>
                                    @endif
                                    <span class="ms-2">SKU {{ $product->sku ?? '—' }}</span>
                                    <span class="ms-2">Updated {{ $product->updated_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light">
                                    <em class="icon ni ni-arrow-left"></em><span>Back</span>
                                </a>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">
                                    <em class="icon ni ni-plus"></em><span>New product</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    @include('admin.product._form', ['mode' => 'edit'])
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

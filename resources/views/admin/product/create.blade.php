@extends('admin.layout.app')
@section('title', 'New product')
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
                                    <li class="breadcrumb-item active">New</li>
                                </ul>
                            </nav>
                            <h3 class="nk-block-title page-title">New product</h3>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light">
                                <em class="icon ni ni-arrow-left"></em><span>Back to products</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    @include('admin.product._form', ['mode' => 'create'])
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

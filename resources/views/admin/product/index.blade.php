@extends('admin.layout.app')
@section('content')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <em class="icon ni ni-check-circle"></em>
                            {{ session('success') }}
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <em class="icon ni ni-cross"></em>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <em class="icon ni ni-cross-circle"></em>
                            {{ session('error') }}
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <em class="icon ni ni-cross"></em>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <em class="icon ni ni-cross-circle"></em>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                <em class="icon ni ni-cross"></em>
                            </button>
                        </div>
                    @endif

                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Products</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="#" data-target="addProduct"
                                                   class="toggle btn btn-primary "><em
                                                        class="icon ni ni-plus"></em><span>Add Product</span></a>

                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="nk-tb-list is-separate mb-3">
                            <div class="nk-tb-item nk-tb-head">
                                <div class="nk-tb-col nk-tb-col-check">
                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                        <input type="checkbox" class="custom-control-input" id="pid">
                                        <label class="custom-control-label" for="pid"></label>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-sm"><span>Name</span></div>
                                <div class="nk-tb-col"><span>SKU</span></div>
                                <div class="nk-tb-col"><span>Price</span></div>
                                <div class="nk-tb-col"><span>Stock</span></div>
                                <div class="nk-tb-col tb-col-md"><span>Category</span></div>
                                <div class="nk-tb-col tb-col-md"><em class="tb-asterisk icon ni ni-star-round"></em>
                                </div>
                                <div class="nk-tb-col"><span>Status</span></div>
                                <div class="nk-tb-col nk-tb-col-tools">
                                    <ul class="nk-tb-actions gx-1 my-n1">
                                        <li class="me-n1">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                   data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Selected</span></a>
                                                        </li>
                                                        <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Selected</span></a>
                                                        </li>
                                                        <li><a href="#"><em class="icon ni ni-bar-c"></em><span>Update Stock</span></a>
                                                        </li>
                                                        <li><a href="#"><em class="icon ni ni-invest"></em><span>Update Price</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- .nk-tb-item -->

                            @forelse($products as $product)
                            <div class="nk-tb-item">
                                <div class="nk-tb-col nk-tb-col-check">
                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                        <input type="checkbox" class="custom-control-input" id="pid{{ $product->id }}">
                                        <label class="custom-control-label" for="pid{{ $product->id }}"></label>
                                    </div>
                                </div>
                                <div class="nk-tb-col tb-col-sm">
                                    <span class="tb-product">
                                        @if($product->featured_image_url)
                                            <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}" class="thumb">
                                        @else
                                            <img src="{{ asset('assets/images/product/placeholder.png') }}" alt="No Image" class="thumb">
                                        @endif
                                        <span class="title">{{ $product->name }}</span>
                                    </span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-sub">{{ $product->sku ?? 'N/A' }}</span>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-lead">$ {{ number_format($product->price, 2) }}</span>
                                    @if($product->sale_price)
                                        <br><small class="text-success">Sale: $ {{ number_format($product->sale_price, 2) }}</small>
                                    @endif
                                </div>
                                <div class="nk-tb-col">
                                    <span class="tb-sub {{ $product->stock <= 10 ? 'text-warning' : '' }}">{{ $product->stock }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    <span class="tb-sub">{{ $product->category->name ?? 'Uncategorized' }}</span>
                                </div>
                                <div class="nk-tb-col tb-col-md">
                                    <div class="asterisk tb-asterisk">
                                        <a href="{{ route('admin.products.toggle-featured', $product) }}" class="toggle-featured">
                                            @if($product->is_featured)
                                                <em class="asterisk-on icon ni ni-star-fill text-warning"></em>
                                            @else
                                                <em class="asterisk-off icon ni ni-star"></em>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="nk-tb-col">
                                    <span class="badge badge-{{ $product->is_active ? 'success' : 'danger' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="nk-tb-col nk-tb-col-tools">
                                    <ul class="nk-tb-actions gx-1 my-n1">
                                        <li class="me-n1">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                   data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="{{ route('admin.products.edit', $product) }}"><em class="icon ni ni-edit"></em><span>Edit Product</span></a>
                                                        </li>
                                                        <li><a href="#"><em class="icon ni ni-eye"></em><span>View Product</span></a>
                                                        </li>
                                                        <li><a href="#"><em
                                                                    class="icon ni ni-activity-round"></em><span>Product Orders</span></a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0" onclick="return confirm('Are you sure you want to delete this product?')">
                                                                    <em class="icon ni ni-trash"></em><span>Remove Product</span>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- .nk-tb-item -->
                            @empty
                            <div class="nk-tb-item">
                                <div class="nk-tb-col" colspan="8">
                                    <div class="text-center py-4">
                                        <em class="icon ni ni-package text-muted" style="font-size: 3rem;"></em>
                                        <p class="mt-2 text-muted">No products found. Add your first product!</p>
                                    </div>
                                </div>
                            </div>
                            @endforelse

                        </div><!-- .nk-tb-list -->

                        @if($products->hasPages())
                        <div class="card">
                            <div class="card-inner">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {{ $products->links() }}
                                    </div>
                                </div><!-- .nk-block-between -->
                            </div>
                        </div>
                        @endif
                    </div><!-- .nk-block -->

                    <!-- Add Product Modal -->
                    <div class="nk-add-product mt-3 toggle-slide toggle-slide-right toggle-screen-any"
                         data-content="addProduct" data-toggle-screen="any" data-toggle-overlay="true"
                         data-toggle-body="true" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: -24px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                         aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                                        <div class="simplebar-content" style="padding: 24px;">
                                            <div class="nk-block-head">
                                                <div class="nk-block-head-content">
                                                    <h5 class="nk-block-title">New Product</h5>
                                                    <div class="nk-block-des">
                                                        <p>Add information and add new product.</p>
                                                    </div>
                                                </div>
                                            </div><!-- .nk-block-head -->
                                            <div class="nk-block">
                                                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="name">Product Name *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                                           id="name" name="name" value="{{ old('name') }}" required>
                                                                    @error('name')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="description">Description</label>
                                                                <div class="form-control-wrap">
                                                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                                                    @error('description')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="price">Regular Price *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror"
                                                                           id="price" name="price" value="{{ old('price') }}" required>
                                                                    @error('price')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="sale_price">Sale Price</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror"
                                                                           id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                                                    @error('sale_price')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="category_id">Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                                                        <option value="">Select Category</option>
                                                                        @foreach($categories ?? [] as $category)
                                                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('category_id')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="featured_image">Featured Image</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                                                           id="featured_image" name="featured_image" accept="image/*">
                                                                    <div class="form-note">Recommended size: 800x800px. Max size: 2MB.</div>
                                                                    @error('featured_image')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="images">Additional Images</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                                                                           id="images" name="images[]" accept="image/*" multiple>
                                                                    <div class="form-note">You can select multiple images. Max size per image: 2MB.</div>
                                                                    @error('images.*')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="is_featured">Featured Product</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="is_active">Active</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary">
                                                                    <em class="icon ni ni-plus"></em><span>Add Product</span>
                                                                </button>
                                                                <a href="#" class="btn btn-outline-secondary toggle" data-target="addProduct">Cancel</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div><!-- .nk-block -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: auto; height: 866px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                            <div class="simplebar-scrollbar"
                                 style="height: 521px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

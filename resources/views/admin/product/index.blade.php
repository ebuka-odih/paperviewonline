@extends('admin.layout.app')
@section('content')

    <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Products</h3>
                                        </div><!-- .nk-block-head-content -->
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li>
                                                            <div class="form-control-wrap">
                                                                <div class="form-icon form-icon-right">
                                                                    <em class="icon ni ni-search"></em>
                                                                </div>
                                                                <input type="text" class="form-control" id="default-04" placeholder="Quick search by id">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white" data-bs-toggle="dropdown">Status</a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span>New Items</span></a></li>
                                                                        <li><a href="#"><span>Featured</span></a></li>
                                                                        <li><a href="#"><span>Out of Stock</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="nk-block-tools-opt">
                                                            <a href="#" data-target="addProduct" class="toggle btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                                                            <a href="#" data-target="addProduct" class="toggle btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Product</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
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
                                            <div class="nk-tb-col tb-col-md"><em class="tb-asterisk icon ni ni-star-round"></em></div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1 my-n1">
                                                    <li class="me-n1">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Selected</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Selected</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-bar-c"></em><span>Update Stock</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-invest"></em><span>Update Price</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!-- .nk-tb-item -->
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="pid1">
                                                    <label class="custom-control-label" for="pid1"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-product">
                                                    <img src="./images/product/a.png" alt="" class="thumb">
                                                    <span class="title">Pink Fitness Tracker</span>
                                                </span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-sub">UY3749</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 99.49</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-sub">49</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">Fitbit, Tracker</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <div class="asterisk tb-asterisk">
                                                    <a href="#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1 my-n1">
                                                    <li class="me-n1">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Product</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>View Product</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Product Orders</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Product</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!-- .nk-tb-item -->

                                    </div><!-- .nk-tb-list -->
                                    <div class="card">
                                        <div class="card-inner">
                                            <div class="nk-block-between-md g-3">
                                                <div class="g">
                                                    <ul class="pagination justify-content-center justify-content-md-start">
                                                        <li class="page-item"><a class="page-link" href="#"><em class="icon ni ni-chevrons-left"></em></a></li>
                                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                        <li class="page-item"><span class="page-link"><em class="icon ni ni-more-h"></em></span></li>
                                                        <li class="page-item"><a class="page-link" href="#">6</a></li>
                                                        <li class="page-item"><a class="page-link" href="#">7</a></li>
                                                        <li class="page-item"><a class="page-link" href="#"><em class="icon ni ni-chevrons-right"></em></a></li>
                                                    </ul><!-- .pagination -->
                                                </div>

                                            </div><!-- .nk-block-between -->
                                        </div>
                                    </div>
                                </div><!-- .nk-block -->
                                <div class="nk-add-product toggle-slide toggle-slide-right toggle-screen-any" data-content="addProduct" data-toggle-screen="any" data-toggle-overlay="true" data-toggle-body="true" data-simplebar="init"><div class="simplebar-wrapper" style="margin: -24px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 24px;">
                                    <div class="nk-block-head">
                                        <div class="nk-block-head-content">
                                            <h5 class="nk-block-title">New Product</h5>
                                            <div class="nk-block-des">
                                                <p>Add information and add new product.</p>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="product-title">Product Title</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="product-title">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-mb-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="regular-price">Regular Price</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="regular-price">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-mb-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="sale-price">Sale Price</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="sale-price">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-mb-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="stock">Stock</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="stock">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-mb-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="SKU">SKU</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="SKU">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="category">Category</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="category">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="tags">Tags</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="tags">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="upload-zone small bg-lighter my-2 dropzone dz-clickable">
                                                    <div class="dz-message">
                                                        <span class="dz-message-text">Drag and drop file</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary"><em class="icon ni ni-plus"></em><span>Add New</span></button>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block -->
                                </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 866px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 521px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection

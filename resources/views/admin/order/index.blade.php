@extends('admin.layout.app')
@section('content')

    <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Orders</h3>
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
                                                                        <li><a href="#"><span>On Hold</span></a></li>
                                                                        <li><a href="#"><span>Delevired</span></a></li>
                                                                        <li><a href="#"><span>Rejected</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="nk-block-tools-opt">
                                                            <a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                                                            <a href="#" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Order</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block">
                                    <div class="nk-tb-list is-separate is-medium mb-3">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="oid">
                                                    <label class="custom-control-label" for="oid"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col"><span>Order</span></div>
                                            <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                                            <div class="nk-tb-col"><span class="d-none d-sm-block">Status</span></div>
                                            <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                                            <div class="nk-tb-col tb-col-md"><span>Purchased</span></div>
                                            <div class="nk-tb-col"><span>Total</span></div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1 my-n1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-edit"></em><span>Update Status</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Orders</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid01">
                                                    <label class="custom-control-label" for="oid01"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95954</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">Jun 4, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-warning d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-warning d-none d-sm-inline-flex">On Hold</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Arnold Armstrong</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">3 Items</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 249.75</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid02">
                                                    <label class="custom-control-label" for="oid02"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95961</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">Jun 3, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-success d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Delivered</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Jean Douglas</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">Pink Fitness Tracker</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 99.49</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid03">
                                                    <label class="custom-control-label" for="oid03"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95963</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 29, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-success d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Delivered</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Ashley Lawson</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">Black Headphones</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 149.75</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid04">
                                                    <label class="custom-control-label" for="oid04"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95933</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 29, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-success d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Delivered</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Joe Larson</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">2 Items</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 199.49</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid05">
                                                    <label class="custom-control-label" for="oid05"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95947</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 28, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-warning d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-warning d-none d-sm-inline-flex">On Hold</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Frances Burns</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">6 Items</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 469.75</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid06">
                                                    <label class="custom-control-label" for="oid06"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95909</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 26, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-success d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Delivered</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Victoria Lynch</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">Waterproof Speaker</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 99.49</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid07">
                                                    <label class="custom-control-label" for="oid07"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95902</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 25, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-warning d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-warning d-none d-sm-inline-flex">On Hold</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Patrick Newman</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">4 Items</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 349.75</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid08">
                                                    <label class="custom-control-label" for="oid08"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95996</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 24, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-warning d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-warning d-none d-sm-inline-flex">On Hold</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Emma Walker</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">Smartwatch</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 129.49</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid09">
                                                    <label class="custom-control-label" for="oid09"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95982</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 23, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-success d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Delivered</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Jane Montgomery</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">Smartwatch</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 249.75</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                                                    <input type="checkbox" class="custom-control-input" id="oid10">
                                                    <label class="custom-control-label" for="oid10"></label>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95959</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">May 23, 2020</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="dot bg-success d-sm-none"></span>
                                                <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Delivered</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <span class="tb-sub">Jane Harris</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub text-primary">Waterproof Speaker</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-lead">$ 99.49</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="Mark as Delivered" data-bs-original-title="Mark as Delivered">
                                                            <em class="icon ni ni-truck"></em></a></li>
                                                    <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" aria-label="View Order" data-bs-original-title="View Order">
                                                            <em class="icon ni ni-eye"></em></a></li>
                                                    <li>
                                                    </li><li>
                                                        <div class="drodown me-n1">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Order</span></a></li>
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
                            </div>
                        </div>
                    </div>
                </div>

@endsection

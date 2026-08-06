<!DOCTYPE html>
<html lang="en" class="js">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
      <title>@yield('title', 'Admin') &middot; {{ config('app.name') }}</title>
      <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.3.0') }}">
      <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=3.3.0') }}">
      @include('admin.layout.styles')
      @stack('styles')
   </head>
   <body class="nk-body bg-lighter npc-general has-sidebar ">
      <div class="nk-app-root">
         <div class="nk-main ">
            <div class="nk-sidebar nk-sidebar-fixed is-dark" data-content="sidebarMenu">
               <div class="nk-sidebar-element nk-sidebar-head">
                  <div class="nk-sidebar-brand">
                     <a href="{{ route('admin.dashboard') }}" class="logo-link nk-sidebar-logo">
                        <h4 class="mb-0 text-white">{{ config('app.name') }}</h4>
                     </a>
                  </div>
                  <div class="nk-menu-trigger me-n2"><a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none"
                     data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a><a
                     href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                     data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a></div>
               </div>
               <div class="nk-sidebar-element">
                  <div class="nk-sidebar-content">
                     <div class="nk-sidebar-menu" data-simplebar>
                        <ul class="nk-menu">
                           <li class="nk-menu-heading">
                              <h6 class="overline-title text-primary-alt">Store</h6>
                           </li>
                           <li class="nk-menu-item {{ request()->routeIs('admin.dashboard') ? 'active current-page' : '' }}">
                              <a href="{{ route('admin.dashboard') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                                 <span class="nk-menu-text">Dashboard</span>
                              </a>
                           </li>
                           <li class="nk-menu-item {{ request()->routeIs('admin.products.*') ? 'active current-page' : '' }}">
                              <a href="{{ route('admin.products.index') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                 <span class="nk-menu-text">Products</span>
                              </a>
                           </li>
                           <li class="nk-menu-item {{ request()->routeIs('admin.categories.*') ? 'active current-page' : '' }}">
                              <a href="{{ route('admin.categories.index') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-tag-fill"></em></span>
                                 <span class="nk-menu-text">Categories</span>
                              </a>
                           </li>
                           <li class="nk-menu-item {{ request()->routeIs('admin.orders.*') ? 'active current-page' : '' }}">
                              <a href="{{ route('admin.orders.index') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-bag-fill"></em></span>
                                 <span class="nk-menu-text">Orders</span>
                                 @if(($pendingOrderCount = \App\Models\Order::where('status', 'pending')->count()) > 0)
                                    <span class="nk-menu-badge badge bg-warning">{{ $pendingOrderCount }}</span>
                                 @endif
                              </a>
                           </li>
                           <li class="nk-menu-heading">
                              <h6 class="overline-title text-primary-alt">Site</h6>
                           </li>
                           <li class="nk-menu-item {{ request()->routeIs('admin.coming-soon.*') ? 'active current-page' : '' }}">
                              <a href="{{ route('admin.coming-soon.index') }}" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-clock"></em></span>
                                 <span class="nk-menu-text">Coming Soon</span>
                              </a>
                           </li>
                           <li class="nk-menu-item">
                              <a href="{{ url('/') }}" target="_blank" class="nk-menu-link">
                                 <span class="nk-menu-icon"><em class="icon ni ni-external-alt"></em></span>
                                 <span class="nk-menu-text">View storefront</span>
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="nk-wrap ">
               <div class="nk-header nk-header-fixed is-light">
                  <div class="container-fluid">
                     <div class="nk-header-wrap">
                        <div class="nk-menu-trigger d-xl-none ms-n1"><a href="#" class="nk-nav-toggle nk-quick-nav-icon"
                           data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a></div>
                        <div class="nk-header-brand d-xl-none">
                           <a href="{{ route('admin.dashboard') }}" class="logo-link">
                              <strong>{{ config('app.name') }}</strong>
                           </a>
                        </div>
                        <div class="nk-header-tools">
                           <ul class="nk-quick-nav">
                              <li class="d-none d-sm-block">
                                 <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                    <em class="icon ni ni-plus"></em><span class="d-none d-md-inline ms-1">New product</span>
                                 </a>
                              </li>
                              <li class="dropdown user-dropdown">
                                 <a href="#" class="dropdown-toggle me-n1" data-bs-toggle="dropdown">
                                    <div class="user-toggle">
                                       <div class="user-avatar sm"><em class="icon ni ni-user-alt"></em></div>
                                       <div class="user-info d-none d-xl-block">
                                          <div class="user-status user-status-active">Administrator</div>
                                          <div class="user-name dropdown-indicator">{{ auth()->user()->name ?? '' }}</div>
                                       </div>
                                    </div>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                                    <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                       <div class="user-card">
                                          <div class="user-avatar"><span>{{ Str::upper(Str::substr(auth()->user()->name ?? 'A', 0, 2)) }}</span></div>
                                          <div class="user-info">
                                             <span class="lead-text">{{ auth()->user()->name ?? '' }}</span>
                                             <span class="sub-text">{{ auth()->user()->email ?? '' }}</span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="dropdown-inner">
                                       <ul class="link-list">
                                          <li><a href="{{ url('/') }}" target="_blank"><em class="icon ni ni-external-alt"></em><span>View storefront</span></a></li>
                                          <li>
                                             <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <em class="icon ni ni-signout"></em><span>Sign out</span>
                                             </a>
                                          </li>
                                       </ul>
                                    </div>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>

               @yield('content')

               <!-- Logout Form -->
               <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                   @csrf
               </form>

               <div class="nk-footer">
                  <div class="container-fluid">
                     <div class="nk-footer-wrap">
                        <div class="nk-footer-copyright">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      {{-- Toasts for flash messages, rendered in one place for every admin screen. --}}
      <div class="pv-toast-stack" id="pvToastStack" aria-live="polite" aria-atomic="true">
         @foreach (['success' => 'check-circle', 'error' => 'cross-circle', 'warning' => 'alert-circle', 'info' => 'info'] as $type => $icon)
            @if (session($type))
               <div class="pv-toast pv-toast-{{ $type }}" role="status">
                  <em class="icon ni ni-{{ $icon }}"></em>
                  <span>{{ session($type) }}</span>
                  <button type="button" class="pv-toast-close" aria-label="Dismiss">&times;</button>
               </div>
            @endif
         @endforeach
         @if ($errors->any())
            <div class="pv-toast pv-toast-error" role="alert">
               <em class="icon ni ni-cross-circle"></em>
               <span>
                  {{ $errors->count() === 1 ? $errors->first() : $errors->count() . ' fields need attention — see the highlighted inputs.' }}
               </span>
               <button type="button" class="pv-toast-close" aria-label="Dismiss">&times;</button>
            </div>
         @endif
      </div>

      <script src="{{ asset('assets/js/bundle.js?ver=3.3.0') }}"></script>
      <script src="{{ asset('assets/js/scripts.js?ver=3.3.0') }}"></script>
      <script>
         // Auto-dismiss toasts, and allow manual close.
         document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#pvToastStack .pv-toast').forEach(function (toast) {
               var close = function () {
                  toast.classList.add('is-leaving');
                  setTimeout(function () { toast.remove(); }, 250);
               };
               toast.querySelector('.pv-toast-close').addEventListener('click', close);
               setTimeout(close, 6000);
            });
         });

         // Guard against double submits on any admin form.
         document.addEventListener('submit', function (event) {
            var form = event.target;
            if (!(form instanceof HTMLFormElement) || form.dataset.allowResubmit === 'true') return;
            var submit = form.querySelector('[type="submit"]');
            if (!submit || submit.disabled) return;
            setTimeout(function () {
               submit.disabled = true;
               submit.dataset.originalText = submit.innerHTML;
               submit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';
            }, 0);
         });
      </script>

      @stack('scripts')
   </body>
</html>

@extends('admin.layout.app')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Settings</h3>
                            <div class="nk-block-des text-soft">
                                <p>Manage your administrator account.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Flash messages are rendered as toasts by admin.layout.app --}}

                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-lg-7">
                            <div class="card card-bordered h-100">
                                <div class="card-inner">
                                    <h5 class="card-title">Change password</h5>
                                    <p class="text-soft">
                                        Enter your current password, then choose a new one of at least 8 characters.
                                    </p>

                                    <form action="{{ route('admin.settings.password.update') }}" method="POST" class="gy-3">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label class="form-label" for="current_password">Current password</label>
                                            <div class="form-control-wrap">
                                                <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="current_password">
                                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                </a>
                                                <input type="password" name="current_password" id="current_password"
                                                       class="form-control form-control-lg @error('current_password') is-invalid @enderror"
                                                       autocomplete="current-password" required>
                                                @error('current_password')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="password">New password</label>
                                            <div class="form-control-wrap">
                                                <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                </a>
                                                <input type="password" name="password" id="password"
                                                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                       autocomplete="new-password" required>
                                                @error('password')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="password_confirmation">Confirm new password</label>
                                            <div class="form-control-wrap">
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                                       class="form-control form-control-lg"
                                                       autocomplete="new-password" required>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2">
                                            <button type="submit" class="btn btn-lg btn-primary">Change password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card card-bordered h-100">
                                <div class="card-inner">
                                    <h5 class="card-title">Account</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0">
                                            <span class="sub-text">Name</span>
                                            <span class="lead-text">{{ $admin->name }}</span>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <span class="sub-text">Email</span>
                                            <span class="lead-text">{{ $admin->email }}</span>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <span class="sub-text">Role</span>
                                            <span class="lead-text text-capitalize">{{ $admin->role }}</span>
                                        </li>
                                    </ul>
                                    <div class="alert alert-light mt-3 mb-0">
                                        <em class="icon ni ni-info-fill"></em>
                                        Changing your password signs out any other device that used
                                        &ldquo;remember me&rdquo;. You stay signed in here.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

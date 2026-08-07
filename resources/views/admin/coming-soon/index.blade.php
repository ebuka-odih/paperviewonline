@extends('admin.layout.app')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Frontpage Lock</h3>
                            <div class="nk-block-des text-soft">
                                <p>Close the storefront front page to visitors and show a message instead.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Flash messages are rendered as toasts by admin.layout.app --}}

                <div class="nk-block">
                    {{-- Current state + the lock/unlock action --}}
                    <div class="card card-bordered mb-3">
                        <div class="card-inner">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gy-3">
                                <div class="me-3">
                                    <h5 class="card-title mb-1">
                                        @if ($settings['enabled'])
                                            <span class="badge bg-danger me-1">
                                                <em class="icon ni ni-lock-alt"></em> Locked
                                            </span>
                                            Visitors cannot reach the front page
                                        @else
                                            <span class="badge bg-success me-1">
                                                <em class="icon ni ni-unlock"></em> Unlocked
                                            </span>
                                            The front page is open
                                        @endif
                                    </h5>
                                    <span class="text-soft">
                                        @if ($settings['enabled'])
                                            They see your message
                                            @if (\App\Models\Setting::comingSoonPasscodeRequired())
                                                and can enter the passcode to get in.
                                            @else
                                                with no way to enter.
                                            @endif
                                        @else
                                            Locking it shows your message in place of the front page.
                                        @endif
                                    </span>
                                </div>
                                <form action="{{ route('admin.coming-soon.toggle') }}" method="POST">
                                    @csrf
                                    @if ($settings['enabled'])
                                        <button type="submit" class="btn btn-lg btn-success">
                                            <em class="icon ni ni-unlock"></em><span>Unlock front page</span>
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-lg btn-danger">
                                            <em class="icon ni ni-lock-alt"></em><span>Lock front page</span>
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    @if ($settings['enabled'] && ! \App\Models\Setting::comingSoonPasscodeRequired())
                        <div class="alert alert-warning">
                            <em class="icon ni ni-alert-circle"></em>
                            The front page is locked with no passcode, so nobody can get in. Set a
                            passcode below if you want to let people through.
                        </div>
                    @endif

                    {{-- Message + passcode --}}
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <h5 class="card-title">Message and passcode</h5>
                            <p class="text-soft">Shown on the front page while it is locked.</p>

                            <form action="{{ route('admin.coming-soon.update') }}" method="POST" class="gy-3">
                                @csrf
                                @method('PUT')

                                <div class="row g-3 align-center">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label" for="message">Message</label>
                                            <span class="form-note">What visitors read while the front page is locked.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <textarea class="form-control @error('message') is-invalid @enderror"
                                                          id="message" name="message" rows="4"
                                                          placeholder="e.g. Dropping soon.">{{ old('message', $settings['message']) }}</textarea>
                                                @error('message')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 align-center">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label">Ask visitors for a passcode</label>
                                            <span class="form-note">Let people in early if they know the passcode.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                {{-- Unchecked switches submit nothing, so pair it with a hidden 0. --}}
                                                <input type="hidden" name="require_password" value="0">
                                                <input type="checkbox" class="custom-control-input" id="require_password"
                                                       name="require_password" value="1"
                                                       {{ old('require_password', $settings['require_password']) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="require_password">Show a passcode box</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 align-center">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label" for="password">Passcode</label>
                                            <span class="form-note">Share this with anyone who should get in early.</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                {{-- Deliberately a text input: this is a shared passcode the admin
                                                     needs to be able to read back and pass on, not a secret. --}}
                                                <input type="text" class="form-control @error('password') is-invalid @enderror"
                                                       id="password" name="password"
                                                       value="{{ old('password', $settings['password']) }}"
                                                       placeholder="e.g. earlyaccess">
                                                @error('password')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-lg-7 offset-lg-5">
                                        <div class="form-group mt-2">
                                            <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Show the admin settings screen.
     */
    public function index()
    {
        return view('admin.settings.index', [
            'admin' => Auth::user(),
        ]);
    }

    /**
     * Change the signed-in admin's own password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'That does not match your current password.',
            'password.confirmed' => 'The new password and its confirmation do not match.',
        ]);

        // The User model casts `password` to `hashed`, so hand it the plain value
        // and let the cast hash it -- calling Hash::make here would be redundant.
        $request->user()->update([
            'password' => $validated['password'],
        ]);

        // Re-issue the session id after a credential change, and drop the remember
        // token so any "remember me" cookie already issued for this account stops
        // working. This admin stays signed in here; other devices get logged out.
        $request->session()->regenerate();
        $request->user()->forceFill(['remember_token' => null])->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Your password has been changed.');
    }
}

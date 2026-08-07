<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Frontpage lock. Presented in the admin as "Frontpage Lock"; the setting keys
 * and route names stay `coming_soon_*` / `coming-soon.*` so existing rows in the
 * settings table keep their values.
 */
class ComingSoonController extends Controller
{
    /**
     * Display the frontpage lock settings page
     */
    public function index()
    {
        $settings = Setting::getComingSoonSettings();

        return view('admin.coming-soon.index', compact('settings'));
    }

    /**
     * Update frontpage lock settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            // A passcode is only meaningful when visitors are asked for one, but it
            // becomes mandatory then -- otherwise the lock has no way through it.
            'require_password' => 'nullable|boolean',
            'password' => 'nullable|string|max:255|required_if:require_password,1',
        ], [
            'password.required_if' => 'Set a passcode, or turn off "Ask visitors for a passcode".',
        ]);

        $requirePassword = $request->boolean('require_password');

        Setting::setValue('coming_soon_message', $validated['message'], 'string', 'coming_soon', 'Message shown on the locked frontpage');
        Setting::setValue('coming_soon_require_password', $requirePassword ? '1' : '0', 'boolean', 'coming_soon', 'Whether visitors can enter a passcode to unlock the frontpage');
        Setting::setValue('coming_soon_password', $validated['password'] ?? '', 'string', 'coming_soon', 'Passcode that unlocks the frontpage');

        return redirect()
            ->route('admin.coming-soon.index')
            ->with('success', 'Frontpage lock settings updated.');
    }

    /**
     * Lock or unlock the frontpage.
     */
    public function toggle(Request $request)
    {
        $enabled = ! Setting::isComingSoonEnabled();

        Setting::setValue('coming_soon_enabled', $enabled ? '1' : '0', 'boolean', 'coming_soon', 'Whether the frontpage is locked to visitors');

        $message = $enabled
            ? 'Frontpage is now locked — visitors see your message instead of the store.'
            : 'Frontpage is now unlocked — the store is open to visitors.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'enabled' => $enabled,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('admin.coming-soon.index')
            ->with('success', $message);
    }
}

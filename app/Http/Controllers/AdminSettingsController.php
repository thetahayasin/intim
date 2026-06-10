<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key');
        return view('admin.settings', compact('settings'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('e.settings', ['tab' => 'password'])
                ->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('e.settings', ['tab' => 'password'])
            ->with('success', 'Password changed successfully.');
    }

    public function updateSmtp(Request $request)
    {
        $request->validate([
            'smtp_host'         => 'required|string|max:255',
            'smtp_port'         => 'required|integer|min:1|max:65535',
            'smtp_username'     => 'nullable|string|max:255',
            'smtp_password'     => 'nullable|string|max:255',
            'smtp_encryption'   => 'nullable|in:tls,ssl,starttls',
            'smtp_from_name'    => 'required|string|max:255',
            'smtp_from_address' => 'required|email|max:255',
        ]);

        $keys = ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_name', 'smtp_from_address'];
        foreach ($keys as $key) {
            Setting::set($key, $request->input($key));
        }

        // Apply at runtime immediately
        config([
            'mail.mailers.smtp.host'       => $request->smtp_host,
            'mail.mailers.smtp.port'       => $request->smtp_port,
            'mail.mailers.smtp.username'   => $request->smtp_username,
            'mail.mailers.smtp.password'   => $request->smtp_password,
            'mail.mailers.smtp.encryption' => $request->smtp_encryption,
            'mail.from.name'               => $request->smtp_from_name,
            'mail.from.address'            => $request->smtp_from_address,
        ]);

        return redirect()->route('e.settings', ['tab' => 'smtp'])
            ->with('success', 'Email settings saved.');
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'site_logo'    => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:512',
        ]);

        if ($request->hasFile('site_logo')) {
            $old = Setting::get('site_logo');
            if ($old && $old !== 'assets/img/logo-mini.png') {
                @unlink(public_path($old));
            }
            $file = $request->file('site_logo');
            $name = 'logo-custom.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img'), $name);
            Setting::set('site_logo', 'assets/img/' . $name);
        }

        if ($request->hasFile('site_favicon')) {
            $old = Setting::get('site_favicon');
            if ($old && $old !== 'favicon.png') {
                @unlink(public_path($old));
            }
            $file = $request->file('site_favicon');
            $name = 'favicon-custom.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img'), $name);
            Setting::set('site_favicon', 'assets/img/' . $name);
        }

        if ($request->filled('delete_logo')) {
            $old = Setting::get('site_logo');
            if ($old && $old !== 'assets/img/logo-mini.png') {
                @unlink(public_path($old));
            }
            Setting::set('site_logo', null);
        }

        if ($request->filled('delete_favicon')) {
            $old = Setting::get('site_favicon');
            if ($old && $old !== 'favicon.png') {
                @unlink(public_path($old));
            }
            Setting::set('site_favicon', null);
        }

        return redirect()->route('e.settings', ['tab' => 'branding'])
            ->with('success', 'Branding updated.');
    }
}

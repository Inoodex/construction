<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'app_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
        ]);

        if ($request->has('app_name')) {
            Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        }

        if ($request->hasFile('app_logo')) {
            $logo = $request->file('app_logo');
            $logoPath = $logo->store('uploads/settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $logoPath]);
        }

        if ($request->hasFile('app_favicon')) {
            $favicon = $request->file('app_favicon');
            $faviconPath = $favicon->store('uploads/settings', 'public');
            Setting::updateOrCreate(['key' => 'app_favicon'], ['value' => $faviconPath]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
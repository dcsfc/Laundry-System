<?php
namespace App\Http\Controllers\SuperAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
class SettingController extends Controller {
    public function index() {
        $settings = Setting::all();
        return view('superadmin.settings.index', compact('settings'));
    }
    public function update(Request $request, Setting $setting) {
        $setting->update($request->all());
        return redirect()->route('superadmin.settings.index');
    }
}

<?php

namespace App\Http\Controllers\Admin\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\WhatsappSetting;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;

class WhatsappAdminController extends Controller
{
    public function settings()
    {
        $settings = [
            'whatsapp_provider' => WhatsappSetting::get('whatsapp_provider'),
            'whatsapp_api_key' => WhatsappSetting::get('whatsapp_api_key'),
            'whatsapp_api_secret' => WhatsappSetting::get('whatsapp_api_secret'),
            'whatsapp_phone_id' => WhatsappSetting::get('whatsapp_phone_id'),
            'whatsapp_webhook_token' => WhatsappSetting::get('whatsapp_webhook_token'),
        ];
        return view('admin.pages.whatsapp.settings', compact('settings'));
    }

    public function saveSettings(Request $request)
    {
        $request->validate([
            'whatsapp_provider' => 'required',
        ]);

        WhatsappSetting::set('whatsapp_provider', $request->whatsapp_provider);
        if ($request->whatsapp_api_key) WhatsappSetting::set('whatsapp_api_key', $request->whatsapp_api_key);
        if ($request->whatsapp_api_secret) WhatsappSetting::set('whatsapp_api_secret', $request->whatsapp_api_secret);
        if ($request->whatsapp_phone_id) WhatsappSetting::set('whatsapp_phone_id', $request->whatsapp_phone_id);
        if ($request->whatsapp_webhook_token) WhatsappSetting::set('whatsapp_webhook_token', $request->whatsapp_webhook_token);

        return redirect()->back()->with('success', 'WhatsApp settings saved successfully.');
    }

    public function templates()
    {
        $templates = WhatsappTemplate::all();
        return view('admin.pages.whatsapp.templates', compact('templates'));
    }

    public function saveTemplate(Request $request)
    {
        $request->validate([
            'template_name' => 'required',
            'template_type' => 'required',
            'content' => 'required',
            'is_active' => 'required|boolean',
        ]);

        WhatsappTemplate::updateOrCreate(
            ['template_name' => $request->template_name],
            $request->only('template_type', 'content', 'is_active')
        );

        return redirect()->back()->with('success', 'Template saved successfully.');
    }
}

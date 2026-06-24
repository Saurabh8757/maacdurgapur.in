@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">WhatsApp Settings</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Provider Configuration</h3>
                        </div>
                        <form action="{{ route('admin::whatsapp.settings.save') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>WhatsApp Provider *</label>
                                    <select name="whatsapp_provider" class="form-control" required>
                                        <option value="stub" {{ ($settings['whatsapp_provider'] ?? '') == 'stub' ? 'selected' : '' }}>Stub (Simulated)</option>
                                        <option value="meta" {{ ($settings['whatsapp_provider'] ?? '') == 'meta' ? 'selected' : '' }}>Meta Cloud API</option>
                                        <option value="twilio" {{ ($settings['whatsapp_provider'] ?? '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number ID (Provider Specific)</label>
                                    <input type="text" name="whatsapp_phone_id" class="form-control" value="{{ $settings['whatsapp_phone_id'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label>API Key / Token (Encrypted)</label>
                                    <input type="password" name="whatsapp_api_key" class="form-control" placeholder="Leave blank to keep existing" value="{{ $settings['whatsapp_api_key'] ? '******' : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>API Secret (Encrypted)</label>
                                    <input type="password" name="whatsapp_api_secret" class="form-control" placeholder="Leave blank to keep existing" value="{{ $settings['whatsapp_api_secret'] ? '******' : '' }}">
                                </div>
                                <div class="form-group">
                                    <label>Webhook Verify Token (Encrypted)</label>
                                    <input type="password" name="whatsapp_webhook_token" class="form-control" placeholder="Leave blank to keep existing" value="{{ $settings['whatsapp_webhook_token'] ? '******' : '' }}">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Config</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Webhook Instructions</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Webhook URL:</strong> <code>{{ url('/api/webhooks/whatsapp') }}</code></p>
                            <p>Point your provider's webhook URL to the above endpoint. This endpoint accepts POST requests and expects incoming message payloads as well as delivery/read status updates.</p>
                            <p>Ensure your Webhook Verify Token matches the one you provide to Meta or Twilio for security validation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

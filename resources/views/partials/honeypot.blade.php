<!-- Author: MEMORA solutions, https://memora.solutions - info@memora.ca -->
@if(config('antispam.enabled', true))

@php
    $honeypotField = config('antispam.honeypot_field', 'website_url');
    $timeTrapField = config('antispam.time_trap_field', '_form_loaded_at');
    $jsTokenField = config('antispam.js_token_field', '_js_token');
@endphp

{{-- 1. HONEYPOT --}}
<div aria-hidden="true" style="position: absolute; left: -9999px; top: -9999px;">
    <label for="{{ $honeypotField }}">{{ __('antispam::messages.honeypot_label') }}</label>
    <input type="text" name="{{ $honeypotField }}" id="{{ $honeypotField }}" tabindex="-1" autocomplete="off" value="">
</div>

{{-- 2. TIME TRAP --}}
<input type="hidden" name="{{ $timeTrapField }}" value="{{ \Illuminate\Support\Facades\Crypt::encrypt(time()) }}">

{{-- 3. JS TOKEN --}}
<div
    x-data="{ jsToken: '' }"
    x-init="setTimeout(() => { jsToken = $el.dataset.token; $refs.jsTokenInput.value = jsToken; }, 100)"
    data-token="{{ hash('sha256', config('app.key') . now()->format('Y-m-d')) }}"
    style="display: none;"
>
    <input type="hidden" name="{{ $jsTokenField }}" x-ref="jsTokenInput" :value="jsToken" value="">
</div>

@endif

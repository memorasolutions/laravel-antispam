<?php

/**
 * @author MEMORA solutions, https://memora.solutions - info@memora.ca
 * @project laravel-antispam
 */

return [

    'enabled' => env('ANTISPAM_ENABLED', true),

    'honeypot_field' => 'website_url',

    'time_trap_field' => '_form_loaded_at',

    'js_token_field' => '_js_token',

    'time_trap_minimum_seconds' => env('ANTISPAM_MIN_SECONDS', 5),

    'rate_limit_max_attempts' => env('ANTISPAM_MAX_ATTEMPTS', 3),

    'rate_limit_decay_seconds' => env('ANTISPAM_DECAY_SECONDS', 3600),

];

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

    'block_bots_enabled' => env('ANTISPAM_BLOCK_BOTS_ENABLED', true),

    'bot_response_status' => env('ANTISPAM_BOT_RESPONSE_STATUS', 403),

    'bot_user_agent_patterns' => [
        '/python-requests/i',
        '/curl\//i',
        '/^Wget/i',
        '/^Go-http-client/i',
        '/^libwww-perl/i',
        '/^Java\//i',
        '/^okhttp/i',
        '/^node-fetch/i',
        '/^axios/i',
        '/scrapy/i',
        '/HttpClient/i',
        '/PostmanRuntime/i',
        '/^Apache-HttpClient/i',
        '/^Faraday/i',
        '/^GuzzleHttp/i',
    ],

];

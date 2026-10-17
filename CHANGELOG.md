# Changelog

## 1.1.0 - 2026-10-17

- New `block-bots` middleware: rejects requests from scripted HTTP clients (python-requests, curl, Wget, Go-http-client, Scrapy...) with a configurable status (default 403)
- Configurable via .env (ANTISPAM_BLOCK_BOTS_ENABLED, ANTISPAM_BOT_RESPONSE_STATUS) and the `bot_user_agent_patterns` list
- NOTICE: attribution limited to MEMORA solutions

## 1.0.0 - 2026-04-15

- Initial release
- Four invisible protection layers: honeypot, time trap, JS token, rate limiting
- Configurable via .env (ANTISPAM_ENABLED, ANTISPAM_MIN_SECONDS, ANTISPAM_MAX_ATTEMPTS, ANTISPAM_DECAY_SECONDS)
- Bilingual translations: English, French
- Publishable config, views, and language files
- Apache 2.0 license
- Compatible with Laravel 11 and 12, PHP 8.2+
- Compatible nwidart/laravel-modules (optional)

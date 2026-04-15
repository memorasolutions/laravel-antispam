<?php

declare(strict_types=1);

/**
 * @author MEMORA solutions, https://memora.solutions - info@memora.ca
 * @project laravel-antispam
 */

namespace Modules\AntiSpam\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class AntiSpam
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (! config('antispam.enabled', true)) {
            return $next($request);
        }

        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        // Layer 1: Honeypot
        if ($this->isHoneypotFilled($request)) {
            return $this->silentSuccess($request);
        }

        // Layer 2: Time Trap
        if (! $this->isTimeTrapValid($request)) {
            return $this->silentSuccess($request);
        }

        // Layer 3: JS Token
        if (! $this->isJsTokenValid($request)) {
            return $this->silentSuccess($request);
        }

        // Layer 4: Rate Limit
        if ($this->isRateLimited($request)) {
            return $this->rateLimitResponse($request);
        }

        $this->cleanRequest($request);

        return $next($request);
    }

    private function isHoneypotFilled(Request $request): bool
    {
        $field = config('antispam.honeypot_field', 'website_url');
        $value = $request->input($field);

        return is_string($value) && $value !== '';
    }

    private function isTimeTrapValid(Request $request): bool
    {
        $field = config('antispam.time_trap_field', '_form_loaded_at');
        $minimumSeconds = (int) config('antispam.time_trap_minimum_seconds', 5);

        $encrypted = $request->input($field);

        if (! is_string($encrypted) || $encrypted === '') {
            return false;
        }

        try {
            $decrypted = Crypt::decrypt($encrypted);
        } catch (\Throwable) {
            return false;
        }

        if (! is_numeric($decrypted)) {
            return false;
        }

        $loadedAt = (int) $decrypted;
        $elapsed = time() - $loadedAt;

        return $elapsed >= $minimumSeconds;
    }

    private function isJsTokenValid(Request $request): bool
    {
        $field = config('antispam.js_token_field', '_js_token');
        $token = $request->input($field);

        if (! is_string($token) || $token === '') {
            return false;
        }

        $expected = hash('sha256', config('app.key') . date('Y-m-d'));

        return hash_equals($expected, $token);
    }

    private function isRateLimited(Request $request): bool
    {
        $maxAttempts = (int) config('antispam.rate_limit_max_attempts', 3);
        $decaySeconds = (int) config('antispam.rate_limit_decay_seconds', 3600);

        $key = 'antispam:' . $request->ip();
        $attempts = (int) Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return true;
        }

        Cache::put($key, $attempts + 1, $decaySeconds);

        return false;
    }

    private function silentSuccess(Request $request): JsonResponse|RedirectResponse
    {
        $message = __('antispam::messages.silent_success');

        if ($request->expectsJson()) {
            return new JsonResponse(data: ['message' => $message], status: SymfonyResponse::HTTP_OK);
        }

        return redirect()->back();
    }

    private function rateLimitResponse(Request $request): JsonResponse|Response
    {
        $message = __('antispam::messages.rate_limit');

        if ($request->expectsJson()) {
            return new JsonResponse(data: ['message' => $message], status: SymfonyResponse::HTTP_TOO_MANY_REQUESTS);
        }

        return new Response(content: $message, status: SymfonyResponse::HTTP_TOO_MANY_REQUESTS);
    }

    private function cleanRequest(Request $request): void
    {
        $fields = [
            (string) config('antispam.honeypot_field', 'website_url'),
            (string) config('antispam.time_trap_field', '_form_loaded_at'),
            (string) config('antispam.js_token_field', '_js_token'),
        ];

        foreach ($fields as $field) {
            $request->request->remove($field);
            $request->query->remove($field);
        }
    }
}

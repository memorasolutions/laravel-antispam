<?php

declare(strict_types=1);

/**
 * @author MEMORA solutions, https://memora.solutions - info@memora.ca
 * @project laravel-antispam
 */

namespace Modules\AntiSpam\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class BlockBots
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (config('antispam.block_bots_enabled', true) === false) {
            return $next($request);
        }

        if (! $request->isMethod('POST')) {
            return $next($request);
        }

        $ua = (string) $request->header('User-Agent', '');

        if ($ua === '') {
            return $this->blockRequest($request);
        }

        $patterns = config('antispam.bot_user_agent_patterns', []);

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $ua)) {
                return $this->blockRequest($request);
            }
        }

        return $next($request);
    }

    private function blockRequest(Request $request): SymfonyResponse
    {
        $status = (int) config('antispam.bot_response_status', 403);

        Log::channel('security')->warning('BlockBots: bot blocked', [
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
            'ip' => $request->ip(),
            'path' => $request->path(),
            'method' => $request->method(),
            'status' => $status,
        ]);

        if ($request->expectsJson()) {
            return new JsonResponse(['message' => 'Forbidden'], $status);
        }

        return new Response('Forbidden', $status);
    }
}

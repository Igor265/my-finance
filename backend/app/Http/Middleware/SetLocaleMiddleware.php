<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    private const SUPPORTED_LOCALES = ['en', 'pt_BR'];

    private const DEFAULT_LOCALE = 'en';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES) ?? self::DEFAULT_LOCALE;

        app()->setLocale($locale);

        return $next($request);
    }
}

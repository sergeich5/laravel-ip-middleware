<?php

namespace Sergeich5\LaravelIpMiddleware\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class IpFilterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    function handle(Request $request, Closure $next)
    {
        if (app()->environment('production') && !$this->isIpInWhiteList($request->ip()))
            abort(403);

        return $next($request);
    }

    private function isIpInWhiteList(string $ip): bool
    {
        return in_array($ip, config('ip-middleware.accept.ips', [])) ||
            in_array($ip, $this->acceptableDomainsToIpList());
    }

    private function acceptableDomainsToIpList(): array
    {
        $acceptableIps = [];

        foreach (config('ip-middleware.accept.domains', []) as $domain)
            $acceptableIps = array_merge($acceptableIps, $this->resolveDomainToIpList($domain));

        return $acceptableIps;
    }

    private function resolveDomainToIpList(string $domain): array
    {
        return Cache::remember(
            sprintf('ip_middleware:domain:%s:ip_list', md5($domain)),
            5 * 60,
            fn() => collect(DNS_get_record($domain, DNS_A))->pluck('ip')
                ->merge(collect(DNS_get_record($domain, DNS_AAAA))->pluck('ipv6'))
                ->toArray()
        );
    }
}

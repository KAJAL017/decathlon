<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectDevice
{
    public function handle(Request $request, Closure $next)
    {
        $isMobile = $this->isMobile($request);

        view()->share('isMobile', $isMobile);

        $request->attributes->set('is_mobile', $isMobile);

        return $next($request);
    }

    protected function isMobile(Request $request): bool
    {
        $agent = $request->userAgent() ?? '';

        if (preg_match('/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini|mobile|phone/i', $agent)) {
            if (preg_match('/ipad|tablet|android(?!.*mobile)/i', $agent)) {
                return false;
            }
            return true;
        }

        return false;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BlockSuspiciosIPs
{
protected $maxAttempts = 5; // Maximum number of allowed attempts
protected $decayMinutes = 1; // Time in minutes to block the IP after exceeding attempts
protected $blockMinutes = 1; // Time in minutes to block the IP permanently

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ipAddress = $request->ip();
        $key = $this->throttleKey($ipAddress);

        if(Cache::has($key.':blocked')) {
            session()->flash('error', 'Your IP address has been blocked for ' . $this->blockMinutes . ' minute(s). Please try again later.');
            return redirect()->back();
        }
            if (Cache::has($key)) {
                $attempts = Cache::increment($key);
                if ($attempts >= $this->maxAttempts) {
                    Cache::put($key.':blocked', true, now()->addMinutes($this->blockMinutes * 60));
                    Log::warning('IP address ' . $ipAddress . ' has been blocked for ' . $this->blockMinutes . ' minute(s).');
                    session()->flash('error', 'Your IP address has been blocked for ' . $this->blockMinutes . ' minute(s). Please try again later.');
                    return redirect()->back();                }
            }
        else {
            Cache::put($key, 1, $this->decayMinutes * 60);
        }


        return $next($request);
    }
    protected function throttleKey($ipAddress)
    {
        return 'suspicious_ip:' . sha1($ipAddress);
    }
}

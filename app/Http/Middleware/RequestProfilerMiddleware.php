<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Providers\AppServiceProvider;
use Exception;

class RequestProfilerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // This is right before the controller/next middleware executes.
        $beforeController = microtime(true);
        $request->attributes->set('profiler_controller_start', $beforeController);
        
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (!defined('LARAVEL_START')) {
            return;
        }

        $endTime = microtime(true);
        $totalTimeMs = ($endTime - LARAVEL_START) * 1000;
        
        $controllerStart = $request->attributes->get('profiler_controller_start', LARAVEL_START);
        $middlewareTimeMs = ($controllerStart - LARAVEL_START) * 1000;
        $controllerTimeMs = ($endTime - $controllerStart) * 1000;

        if ($totalTimeMs > 2000) { // Log requests taking > 2 seconds
            $route = $request->route();
            $routeName = $route ? $route->getName() : 'N/A';
            $action = $route ? $route->getActionName() : 'N/A';
            
            $queryCount = AppServiceProvider::$queryCount ?? 0;
            $peakMemoryMb = memory_get_peak_usage(true) / 1024 / 1024;
            
            $logData = [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'route_name' => $routeName,
                'action' => $action,
                'auth_id' => auth()->id() ?? 'guest',
                'total_duration_ms' => round($totalTimeMs, 2),
                'middleware_duration_ms' => round($middlewareTimeMs, 2),
                'controller_view_duration_ms' => round($controllerTimeMs, 2),
                'query_count' => $queryCount,
                'peak_memory_mb' => round($peakMemoryMb, 2)
            ];

            if ($totalTimeMs > 3000) {
                // Attach compact stack trace if it exceeded 3 seconds
                $e = new Exception();
                $trace = collect($e->getTrace())
                            ->take(10)
                            ->map(function ($item) {
                                return ($item['file'] ?? 'Unknown') . ':' . ($item['line'] ?? '?') . ' -> ' . ($item['function'] ?? '?');
                            })->implode("\n");
                $logData['stack_trace'] = $trace;
                Log::critical('SLOW REQUEST (>3s)', $logData);
            } else {
                Log::warning('SLOW REQUEST (>2s)', $logData);
            }
        }
    }
}

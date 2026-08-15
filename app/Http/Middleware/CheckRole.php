<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle($request, Closure $next, $role)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    $user = auth()->user();

    // إيلا كان الدور كيتطابق مع المطلوب
    if ($user->role === $role) {
        return $next($request);
    }

    // وإلا، كنوجّهوه على حسب الدور ديالو الحقيقي
    if ($user->role === 'client') {
        return redirect()->route('client.dashboard');
    }

    return redirect()->route('dashboard'); // البلاصة ديال الأدمن والتقنيين
}
}

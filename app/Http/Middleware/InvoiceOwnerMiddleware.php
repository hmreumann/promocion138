<?php

namespace App\Http\Middleware;

use App\Models\Invoice;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InvoiceOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $invoice = $request->route('invoice');
        
        // Allow admins to access any invoice
        if (Auth::check() && in_array(Auth::user()->email, config('admins.emails'))) {
            return $next($request);
        }
        
        if ($invoice instanceof Invoice && $invoice->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta factura.');
        }
        
        return $next($request);
    }
}

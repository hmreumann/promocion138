<?php

namespace App\Http\Middleware;

use App\Models\PollToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidatePollToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = PollToken::where('token', $request->route('token'))->first();

        if (! $token) {
            abort(404, 'Token de encuesta no válido.');
        }

        if ($token->expires_at < now()) {
            return redirect()->route('welcome')
                ->with('error', 'Este enlace ha expirado.');
        }

        if ($token->used_at) {
            return redirect()->route('polls.results', $token->token)
                ->with('info', 'Ya ha respondido esta encuesta. Puede ver los resultados.');
        }

        $request->merge(['poll_token' => $token]);

        return $next($request);
    }
}

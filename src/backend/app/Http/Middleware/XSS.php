<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TagClosedValidationService;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!app(TagClosedValidationService::class)->checkStringForAllowedTags($request->comment_text))
        {
            abort(403, "Tags not valid");
        }
        if (!app(TagClosedValidationService::class)->checkString($request->comment_text))
        {
            abort(403, "Tags not valid");
        }
        
        // $request->merge(['comment_text' => $cleanedContent]);
        return $next($request);
    }
}

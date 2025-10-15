<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ArticleView;

class TrackArticleViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Сохраняем просмотр если это страница статьи
        if ($request->route()->getName() == 'article.show' && $request->route('article')) {
            ArticleView::create([
                'article_id' => $request->route('article')->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
        
        return $response;
    }
}


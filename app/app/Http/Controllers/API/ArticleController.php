<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Events\NewArticleEvent;

class ArticleController extends Controller
{
    public function index()
    {
        $currentPage = request()->get('page', 1);
        
        $articles = Cache::remember('api_articles_page_' . $currentPage, 60, function () {
            return Article::latest()->paginate(5);
        });
        
        return response()->json($articles);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);
        
        $request->validate([
            'date_public' => 'required|date',
            'title' => 'required|min:10',
            'text' => 'max:100'
        ]);

        $article = Article::create([
            'date_public' => $request->date_public,
            'title' => $request->title,
            'text' => $request->text,
            'users_id' => auth()->id(),
        ]);

        // Очищаем кэш
        $page = 1;
        while (Cache::has('api_articles_page_' . $page)) {
            Cache::forget('api_articles_page_' . $page);
            $page++;
        }
        if($article->save()){
            NewArticleEvent::dispatch($article);
        }

        return response()->json([
            'message' => 'Article created successfully',
            'data' => $article
        ], 201);
    }

    public function show(Article $article)
    {
        $comments = Comment::where('article_id', $article->id)
                          ->where('accept', true)
                          ->get();

        return response()->json([
            'article' => $article,
            'comments' => $comments
        ]);
    }

    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);
        
        $request->validate([
            'date_public' => 'required|date',
            'title' => 'required|min:10',
            'text' => 'max:100'
        ]);

        $article->update([
            'date_public' => $request->date_public,
            'title' => $request->title,
            'text' => $request->text,
        ]);

        Cache::flush();

        return response()->json([
            'message' => 'Article updated successfully',
            'data' => $article
        ]);
    }

    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);
        
        $article->delete();
        
        Cache::flush();

        return response()->json([
            'message' => 'Article deleted successfully'
        ]);
    }
}
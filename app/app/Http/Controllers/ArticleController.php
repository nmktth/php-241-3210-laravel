<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Events\NewArticleEvent;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentPage = request()->get('page', 1);
        
        $articles = Cache::remember('articles_page_' . $currentPage, 60, function () {
            return Article::latest()->paginate(5);
        });
        
        return view('/article/article', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Article::class);
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|min:10',
            'text' => 'max:100'
        ]);
        $article = new Article;
        $article->date_public = $request->date;
        $article->title = request('title');
        $article->text = $request->text;
        $article->users_id = auth()->id();
        if($article->save()){
            NewArticleEvent::dispatch($article);
        }
        
        // Удаляем кэш главной страницы и всех страниц пагинации
        $page = 1;
        while (Cache::has('articles_page_' . $page)) {
            Cache::forget('articles_page_' . $page);
            $page++;
        }
        
        return redirect()->route('article.index')->with('message','Create successful');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        // Удаляем уведомления для этой статьи
        if (auth()->check()) {
            auth()->user()->notifications()
                ->where('type', 'App\Notifications\NewCommentNotify')
                ->where('data->comment->article_id', $article->id)
                ->delete();
        }
        
    $cacheKey = 'article_show_' . $article->id;
    
    $data = Cache::rememberForever($cacheKey, function () use ($article) {
        $comments = Comment::where('article_id', $article->id)
                            ->where('accept', true)
                            ->get();
        
        return [
            'article' => $article,
            'comments' => $comments
        ];
    });
    
    return view('article.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('restore', $article);
        return view('article.edit', ['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|min:10',
            'text' => 'max:100'
        ]);
        
        $article->date_public = $request->date;
        $article->title = request('title');
        $article->text = $request->text;
        $article->users_id = 1;
        $article->save();
        
        // Очищаем весь кэш
        Cache::flush();
        
        return redirect()->route('article.show', ['article'=>$article->id])->with('message','Update successful');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);
        $article->delete();
        
        // Очищаем весь кэш
        Cache::flush();
        
        return redirect()->route('article.index')->with('message','Delete successful');
    }
}
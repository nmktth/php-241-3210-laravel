<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(5);
        return view('/article/article', ['articles'=>$articles]);
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
        $article->save();
        return redirect()->route('article.index')->with('message','Create successful');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $comments = Comment::where('article_id', $article->id)
                            ->where('accept', true)
                            ->get();
        return view('article.show', ['article'=>$article, 'comments'=>$comments]);
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
        return redirect()->route('article.show', ['article'=>$article->id])->with('message','Update successful');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);
        $article->delete();
        return redirect()->route('article.index')->with('message','Delete successful');
    }
}
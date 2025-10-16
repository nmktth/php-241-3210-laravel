<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use App\Jobs\VeryLongJob;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->paginate(10);
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'min:10|required',
            'article_id' => 'required|exists:articles,id'
        ]);

        $article = Article::findOrFail($request->article_id);

        $comment = Comment::create([
            'text' => $request->text,
            'article_id' => $request->article_id,
            'user_id' => auth()->id(),
        ]);
        VeryLongJob::dispatch($article, $comment, auth()->user()->name);

        Cache::forget('article_show_' . $request->article_id);

        return response()->json([
            'message' => 'Comment added successfully and awaiting moderation',
            'data' => $comment
        ], 201);
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('comment', $comment);

        $validated = $request->validate([
            'text' => 'min:10|required',
        ]);

        $comment->update(['text' => $validated['text']]);
        
        Cache::forget('article_show_' . $comment->article_id);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('comment', $comment);
        
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function accept(Comment $comment)
    {
        Gate::authorize('comment.accept', $comment);
        
        $comment->update(['accept' => true]);
        
        Cache::forget('article_show_' . $comment->article_id);

        return response()->json([
            'message' => 'Comment accepted'
        ]);
    }

    public function reject(Comment $comment)
    {
        Gate::authorize('comment.reject', $comment);
        
        $comment->update(['accept' => false]);

        return response()->json([
            'message' => 'Comment rejected'
        ]);
    }
}
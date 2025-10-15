<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Article;;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Jobs\VeryLongJob;
use App\Notifications\NewCommentNotify;


class CommentController extends Controller
{
    public function index(){
        $comments = Comment::latest()->paginate(10);
        return view('comment.index', ['comments'=>$comments]);
    }

    public function store(Request $request){
        $request->validate([
            'text'=>'min:10|required',
        ]);
        $article = Article::FindOrFail($request->article_id);
        $comment = new Comment;
        $comment-> text = $request->text;
        $comment->article_id = $request->article_id;
        $comment->user_id = auth()->id();
        if($comment->save())
            VeryLongJob::dispatch($article, $comment, auth()->user()->name);
        return redirect()->route('article.show', $request->article_id)->with('message', "Comment add succesful and enter for moderation");
    }

    public function edit(Comment $comment)
    {
        Gate::authorize('comment', $comment);
        return view('comment.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('comment', $comment);

        $validated = $request->validate([
            'text' => 'min:10|required',
        ]);

        $comment->update(['text' => $validated['text']]);
        
        return redirect()
            ->route('article.show', $comment->article_id)
            ->with('message', 'Комментарий обновлён');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('comment', $comment);
        
        $articleId = $comment->article_id;
        $comment->delete();
        
        return redirect()
            ->route('article.show', $articleId)
            ->with('message', 'Комментарий удалён');
    }


    public function accept(Comment $comment)
    {
        Gate::authorize('comment.accept', $comment);
        $comment->accept = true;
        $users = User::where('id', '!=', $comment->user_id)->get();
        if($comment->save()){
            // Получаем всех пользователей кроме автора комментария
            $users = User::where('id', '!=', $comment->user_id)->get();
            
            // Отправляем уведомления только читателям (не аутентифицированным в данной сессии)
            Notification::send($users, new NewCommentNotify($comment));
        }
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment)
    {
        Gate::authorize('comment.reject', $comment);
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.index');
    }

    
}
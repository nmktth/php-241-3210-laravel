@extends('layout')
@section('content')
 @if(session()->has('message'))
  <div class="alert alert-success" role="alert">
      {{session('message')}}
  </div>
@endif

<table class="table">
  <thead>
    <tr>
      <th scope="col">Date public</th>
      <th scope="col">Author</th>
      <th scope="col">Article</th>
      <th scope="col">Text</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($comments as $comment)
    <tr>
      <th scope="row">{{$comment->created_at}}</th>
      <td>{{App\Models\User::FindOrFail($comment->user_id)->name}}</td>
      <td><a href="/article/{{App\Models\Article::FindOrFail($comment->article_id)->id}}">{{App\Models\Article::FindOrFail($comment->article_id)->title}}</a></td>
      <td>{{$comment->text}}</td>
      <td>
        @if(!$comment->accept)  
            <a href="/comment/accept/{{$comment->id}}" class="btn btn-primary">Accept</a>
        @else
            <a href="/comment/reject/{{$comment->id}}" class="btn btn-warning">Reject</a>
        @endif
       </td>
    </tr>
    @endforeach
  </tbody>
</table>
{{$comments->links()}}
@endsection
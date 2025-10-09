@extends('layout')
@section('content')

 @if(session()->has('message'))
  <div class="alert alert-success" role="alert">
      {{session('message')}}
  </div>
@endif

<div class="card" style="width: 100%;">
  <div class="card-body">
    <h5 class="card-title text-center ">{{$article->title}}</h5>
    <h6 class="card-subtitle mb-2 text-body-secondary">{{$article->date_public}}</h6>
    <p class="card-text">{{$article->text}}</p>
    @can('create')
    <div class="btn-toolbar mt-3" role="toolbar">
    <a href="/article/{{$article->id}}/edit" class="btn btn-primary me-3">Edit article</a>
    <form action="/article/{{$article->id}}" method="post">
        @METHOD("DELETE")
        @CSRF
        <button type="submit" class="btn btn-warning me-3">Delete article</button>
    </form>
    </div>    
    @endcan
  </div>
</div>

<h2>New Comment</h2>
<ul class="list-group mb-3">
    @foreach($errors->all() as $error)
        <li class="list-group-item list-group-item-danger">{{$error}}</li>
    @endforeach
  </ul>
<form action="/comment" method="POST">
  @CSRF
  <div class="mb-3">
    <label for="text" class="form-label">Enter comment</label>
    <textarea name="text" id="text" class="form-control"></textarea>
  </div>
  <input type="hidden" name="article_id" value="{{$article->id}}">
  <button type="submit" class="btn btn-primary">Save</button>
</form>
@foreach($comments as $comment)
<div class="card" style="width: 38rem;">
  <div class="card-body">
    <p class="card-text">{{$comment->text}}</p>
    <div class="btn-toolbar mt-3" role="toolbar">
    @can('comment', $comment)
      <a href="/comment/edit/{{$comment->id}}" class="btn btn-primary me-3">Edit comment</a>
      <a href="/comment/delete/{{$comment->id}}" class="btn btn-primary me-3">Delete comment</a>
    @endcan
    </div>    
  </div>
</div>
@endforeach
@endsection
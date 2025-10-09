@extends('layout')
@section('content')

  <ul class="list-group mb-3">
    @foreach($errors->all() as $error)
        <li class="list-group-item list-group-item-danger">{{$error}}</li>
    @endforeach
  </ul>
<form action="/article/{{$article->id}}" method="POST">
  @CSRF
  @METHOD('PUT')
  <div class="mb-3">
    <label for="date" class="form-label">Enter date public</label>
    <input type="date" class="form-control" id="date" name="date" value="{{$article->date_public}}">
  </div>
  <div class="mb-3">
    <label for="title" class="form-label">Enter title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{$article->title}}">
  </div>
  <div class="mb-3">
    <label for="text" class="form-label">Enter description</label>
    <textarea name="text" id="text" class="form-control">{{$article->text}}"</textarea>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
@extends('layout')
@section('content')

<h2>Edit Comment</h2>

<form action="{{ route('comment.update', $comment) }}" method="POST">
  @csrf
  @method('PUT')
  <div class="mb-3">
    <label for="text" class="form-label">Comment text</label>
    <textarea name="text" id="text" class="form-control">{{ old('text', $comment->text) }}</textarea>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
  <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection

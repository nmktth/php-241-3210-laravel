@extends('layout')
@section('content')
    <div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">{{$contact['name']}}</h5>
    <h6 class="card-subtitle mb-2 text-body-secondary">{{$contact['adres']}}</h6>
    <p class="card-text">{{$contact['phone']}}</p>
    <a href="#" class="card-link">{{$contact['email']}}</a>
  </div>
</div>
@endsection
@extends('layouts.material_errors')

@section('content')
  <div class="text-center">
    <h1>Account scaduto.</h1>
    <a href="{{ url('logout') }}" class="btn btn-lg btn-raised btn-link">Esci</a>
  </div>
@endsection

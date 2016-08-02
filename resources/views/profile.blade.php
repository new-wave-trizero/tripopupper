@extends('layouts.material')

@section('content')

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Modifica Dati Profilo</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('profile') }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}

      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label">Nome</label>
        <input name="name" type="text" class="form-control" value="{{ old('name', Auth::user()->name) }}">
        <span class="help-block">{{ $errors->first('name') }}</span>
      </div>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email', Auth::user()->email) }}">
        <span class="help-block">{{ $errors->first('email') }}</span>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Modfica Dati</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Cambia Password</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('profile/password') }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}

      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="control-label">Password</label>
        <input name="password" type="password" class="form-control" />
        <span class="help-block">{{ $errors->first('password') }}</span>
      </div>
      <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label class="control-label">Ripeti Password</label>
        <input name="password_confirmation" type="password" class="form-control" />
        <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Cambia Password</button>
      </div>
    </form>
  </div>
</div>

@endsection

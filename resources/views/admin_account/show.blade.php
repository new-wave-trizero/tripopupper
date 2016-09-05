@extends('layouts.material')

@section('content')

<div class="row">
  <div class="col-md-9">
    <h4>Amministratore <strong>{{ $adminUser->name }}</strong></h4>
  </div>
  <div class="col-md-3">
    <div class="btn-group-sm popup-actions-edit">
        @can('login-as-another-user', $adminUser)
          <form style="display:inline" method="POST" action="{{ url('/login-as/' . $adminUser->id) }}">
            {{ csrf_field() }}
            <button
               class="btn btn-success btn-fab btn-fab-mini"
               title="Loggati come {{ $adminUser->name }}">
              <i class="material-icons">assignment_ind</i></button>
          </form>
        @endcan
        <form style="display:inline" method="POST" action="{{ url('/admin-account/' . $adminUser->id) }}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button
             class="btn btn-danger btn-fab btn-fab-mini ask-confirm"
             data-confirm-title="Cliente {{ $adminUser->name }}"
             data-confirm-body="Sei sicuro di voler eliminare l'amministratore <strong>{{ $adminUser->name }}</strong>?"
             data-confirm-btn-title="Elimina"
             data-confirm-btn-class="btn-danger"
             title="Elimina amministratore {{ $adminUser->name }}">
            <i class="material-icons">delete</i></button>
        </form>
    </div>
  </div>
</div>

<br />

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Modifica Dati Amministratore {{ $adminUser->name }}</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('/admin-account/' . $adminUser->id) }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label">Nome</label>
        <input name="name" type="text" class="form-control" value="{{ old('name', $adminUser->name) }}">
        <span class="help-block">{{ $errors->first('name') }}</span>
      </div>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email', $adminUser->email) }}">
        <span class="help-block">{{ $errors->first('email') }}</span>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Modifica</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">{{ $adminUser->name }}'s Popups</h3>
  </div>
  <div class="panel-body">
    @include('partials.popup.list', ['popups' => $popups])
  </div>
</div>

@endsection

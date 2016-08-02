@extends('layouts.material')

@section('content')

<div class="row">
  <div class="col-md-9">
    <h4>Cliente <strong>{{ $customerUser->name }}</strong></h4>
  </div>
  <div class="col-md-3">
    <div class="btn-group-sm popup-actions-edit">
        @can('login-as-another-user', $customerUser)
          <form style="display:inline" method="POST" action="{{ url('/login-as/' . $customerUser->id) }}">
            {{ csrf_field() }}
            <button
               class="btn btn-success btn-fab btn-fab-mini"
               title="Loggati come {{ $customerUser->name }}">
              <i class="material-icons">assignment_ind</i></button>
          </form>
        @endcan
        <form style="display:inline" method="POST" action="{{ url('/customer-account/' . $customerUser->id) }}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button
             class="btn btn-danger btn-fab btn-fab-mini ask-confirm"
             data-confirm-title="Cliente {{ $customerUser->name }}"
             data-confirm-body="Sei sicuro di voler eliminare il cliente <strong>{{ $customerUser->name }}</strong>?"
             data-confirm-btn-title="Elimina"
             data-confirm-btn-class="btn-danger"
             title="Elimina cliente {{ $customerUser->name }}">
            <i class="material-icons">delete</i></button>
        </form>
    </div>
  </div>
</div>

<br />

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Modifica Dati Cliente {{ $customerUser->name }}</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('/customer-account/' . $customerUser->id) }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label">Nome</label>
        <input name="name" type="text" class="form-control" value="{{ old('name', $customerUser->name) }}">
        <span class="help-block">{{ $errors->first('name') }}</span>
      </div>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email', $customerUser->email) }}">
        <span class="help-block">{{ $errors->first('email') }}</span>
      </div>
      @if (Auth::user()->isAdmin())
        <div class="form-group{{ $errors->has('valid_until') ? ' has-error' : '' }}">
          <label class="control-label">Scandenza Account</label>
          <input name="valid_until" type="date" class="form-control" value="{{ old('valid_until', $customerUser->customerAccount->valid_until->toDateString()) }}">
          <span class="help-block">{{ $errors->first('valid_until') }}</span>
        </div>
      @endif
      <div class="form-group{{ $errors->has('can_create_popups') ? ' has-error' : '' }}">
        <div class="checkbox">
          <label>
            <input name="can_create_popups" value="0" type="hidden" />
            <input name="can_create_popups" value="1" type="checkbox"
            {{ old('can_create_popups', (string)$customerUser->customerAccount->can_create_popups) === '1' ? 'checked' : '' }} /> Possibilità di creare popup
          </label>
        </div>
        <p class="help-block">{{ $errors->first('can_create_popups') }}</p>
      </div>
      <div class="form-group{{ $errors->has('can_delete_popups') ? ' has-error' : '' }}">
        <div class="checkbox">
          <label>
            <input name="can_delete_popups" value="0" type="hidden" />
            <input name="can_delete_popups" value="1" type="checkbox"
              {{ old('can_delete_popups', (string)$customerUser->customerAccount->can_delete_popups) === '1' ? 'checked' : '' }} /> Possibilità di eliminare popup
          </label>
        </div>
        <p class="help-block">{{ $errors->first('can_delete_popups') }}</p>
      </div>
      <div class="form-group{{ $errors->has('can_update_popups_domains') ? ' has-error' : '' }}">
        <div class="checkbox">
          <label>
            <input name="can_update_popups_domains" value="0" type="hidden" />
            <input name="can_update_popups_domains" value="1" type="checkbox"
              {{ old('can_update_popups_domains', (string)$customerUser->customerAccount->can_update_popups_domains) === '1' ? 'checked' : '' }} /> Possibilità di modificare il dominio dei popup
          </label>
        </div>
        <p class="help-block">{{ $errors->first('can_update_popups_domains') }}</p>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Modifica</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">{{ $customerUser->name }}'s Popups</h3>
  </div>
  <div class="panel-body">
    @include('partials.popup.list', ['popups' => $popups])
  </div>
</div>

@endsection

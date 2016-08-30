@extends('layouts.material')

@section('content')

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Crea Account Cliente</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('/customer-account') }}">
      {{ csrf_field() }}

      @if ($errors->has('max_member_customers'))
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">×</button>
          <div>{{ $errors->first('max_member_customers') }}</div>
        </div>
      @endif

      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label">Nome</label>
        <input name="name" type="text" class="form-control" value="{{ old('name') }}">
        <span class="help-block">{{ $errors->first('name') }}</span>
      </div>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email') }}">
        <span class="help-block">{{ $errors->first('email') }}</span>
      </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="control-label">Password</label>
        <input name="password" type="password" class="form-control" value="{{ old('password') }}">
        <span class="help-block">{{ $errors->first('password') }}</span>
      </div>
      <div class="form-group{{ $errors->has('valid_until') ? ' has-error' : '' }}">
        <label class="control-label">Scandenza Account</label>
        <input name="valid_until" type="date" class="form-control" value="{{ old('valid_until') }}">
        <span class="help-block">{{ $errors->first('valid_until') }}</span>
      </div>
      <div class="form-group{{ $errors->has('can_create_popups') ? ' has-error' : '' }}">
        <div class="checkbox">
          <label>
            <input name="can_create_popups" value="0" type="hidden" />
            <input name="can_create_popups" value="1" type="checkbox"
                   {{ old('can_create_popups') === '1' ? 'checked' : '' }} /> Possibilità di creare popup
          </label>
        </div>
        <p class="help-block">{{ $errors->first('can_create_popups') }}</p>
      </div>
      <div class="form-group{{ $errors->has('can_delete_popups') ? ' has-error' : '' }}">
        <div class="checkbox">
          <label>
            <input name="can_delete_popups" value="0" type="hidden" />
            <input name="can_delete_popups" value="1" type="checkbox"
                   {{ old('can_delete_popups') === '1' ? 'checked' : '' }} /> Possibilità di eliminare popup
          </label>
        </div>
        <p class="help-block">{{ $errors->first('can_delete_popups') }}</p>
      </div>
      <div class="form-group{{ $errors->has('can_update_popups_domains') ? ' has-error' : '' }}">
        <div class="checkbox">
          <label>
            <input name="can_update_popups_domains" value="0" type="hidden" />
            <input name="can_update_popups_domains" value="1" type="checkbox"
                   {{ old('can_update_popups_domains') === '1' ? 'checked' : '' }} /> Possibilità di modificare il dominio dei popup
          </label>
        </div>
        <p class="help-block">{{ $errors->first('can_update_popups_domains') }}</p>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Crea</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Clienti</h3>
  </div>
  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Mail</th>
          <th>Creazione Popup</th>
          <th>Eliminazione Popup</th>
          <th>Modifica Dominio Popup</th>
          <th>Scadenza</th>
          <th><i class="material-icons">more_vert</i></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($customerUsers as $customerUser)
          <tr>
            <td>{{ $customerUser->name }}</td>
            <td>{{ $customerUser->email }}</td>
            <td>
              @if ($customerUser->customerAccount->can_create_popups)
                <i class="material-icons" style="color: green">done</i>
              @else
                <i class="material-icons" style="color: red">close</i>
              @endif
            </td>
            <td>
              @if ($customerUser->customerAccount->can_delete_popups)
                <i class="material-icons" style="color: green">done</i>
              @else
                <i class="material-icons" style="color: red">close</i>
              @endif
            </td>
            <td>
              @if ($customerUser->customerAccount->can_update_popups_domains)
                <i class="material-icons" style="color: green">done</i>
              @else
                <i class="material-icons" style="color: red">close</i>
              @endif
            </td>
            <td>{{ is_null($customerUser->customerAccount->valid_until) ? 'Illimitato' : $customerUser->customerAccount->valid_until->toDateString() }}</td>
            <td>
              <div class="btn-group-sm">
                <a href="{{ url('/customer-account/' . $customerUser->id) }}"
                   class="btn btn-info btn-fab btn-fab-mini"
                   title="Modifica cliente {{ $customerUser->name }}">
                  <i class="material-icons">mode_edit</i>
                </a>
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
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>


@endsection

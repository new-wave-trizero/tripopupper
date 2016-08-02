@extends('layouts.material')

@section('content')

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Crea Account Agenzia</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('/agency-account') }}">
      {{ csrf_field() }}
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
      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Crea</button>
      </div>
    </form>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Agenzie</h3>
  </div>
  <div class="panel-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Mail</th>
          <th>Scadenza</th>
          <th><i class="material-icons">more_vert</i></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($agencyUsers as $agencyUser)
          <tr>
            <td>{{ $agencyUser->name }}</td>
            <td>{{ $agencyUser->email }}</td>
            <td>{{ $agencyUser->agencyAccount->valid_until->toDateString() }}</td>
            <td>
              <div class="btn-group-sm">
                @can('login-as-another-user', $agencyUser)
                  <form style="display:inline" method="POST" action="{{ url('/login-as/' . $agencyUser->id) }}">
                    {{ csrf_field() }}
                    <button
                       class="btn btn-success btn-fab btn-fab-mini"
                       title="Loggati come {{ $agencyUser->name }}">
                      <i class="material-icons">assignment_ind</i></button>
                  </form>
                @endcan
                <form style="display:inline" method="POST" action="{{ url('/agency-account/' . $agencyUser->id) }}">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <button
                     class="btn btn-danger btn-fab btn-fab-mini ask-confirm"
                     data-confirm-title="Agenzia {{ $agencyUser->name }}"
                     data-confirm-body="Sei sicuro di voler eliminare l'agenzia <strong>{{ $agencyUser->name }}</strong>?"
                     data-confirm-btn-title="Elimina"
                     data-confirm-btn-class="btn-danger"
                     title="Elimina agenzia {{ $agencyUser->name }}">
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

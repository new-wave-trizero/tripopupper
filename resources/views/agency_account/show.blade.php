@extends('layouts.material')

@section('content')

<div class="row">
  <div class="col-md-9">
    <h4>Agenzia <strong>{{ $agencyUser->name }}</strong></h4>
  </div>
  <div class="col-md-3">
    <div class="btn-group-sm popup-actions-edit">
        @can('login-as-another-user', $agencyUser)
          <form style="display:inline" method="POST" action="{{ url('/login-as/' . $agencyUser->id) }}">
            {{ csrf_field() }}
            <button
               class="btn btn-success btn-fab btn-fab-mini"
               title="Loggati come {{ $agencyUser->name }}">
              <i class="material-icons">assignment_ind</i></button>
          </form>
        @endcan
        <form style="display:inline" method="POST" action="{{ url('/customer-account/' . $agencyUser->id) }}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button
             class="btn btn-danger btn-fab btn-fab-mini ask-confirm"
             data-confirm-title="Cliente {{ $agencyUser->name }}"
             data-confirm-body="Sei sicuro di voler eliminare l'agenzia <strong>{{ $agencyUser->name }}</strong>?"
             data-confirm-btn-title="Elimina"
             data-confirm-btn-class="btn-danger"
             title="Elimina agenzia {{ $agencyUser->name }}">
            <i class="material-icons">delete</i></button>
        </form>
    </div>
  </div>
</div>

<br />

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Modifica Dati Agenzia {{ $agencyUser->name }}</h3>
  </div>
  <div class="panel-body">
    <form method="post" action="{{ url('/agency-account/' . $agencyUser->id) }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label">Nome</label>
        <input name="name" type="text" class="form-control" value="{{ old('name', $agencyUser->name) }}">
        <span class="help-block">{{ $errors->first('name') }}</span>
      </div>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email', $agencyUser->email) }}">
        <span class="help-block">{{ $errors->first('email') }}</span>
      </div>
      <div class="form-group{{ $errors->has('valid_until') ? ' has-error' : '' }}">
        <label class="control-label">Scandenza Account</label>
        <input name="valid_until" type="date" class="form-control"
               value="{{ old('valid_until', is_null($agencyUser->agencyAccount->valid_until) ? '' : $agencyUser->agencyAccount->valid_until->toDateString()) }}">
        <span class="help-block">{{ $errors->first('valid_until') }}</span>
      </div>
      <div class="form-group">
        <label class="control-label">Numero Clienti</label>
        <input name="valid_until" type="text" class="form-control" value="{{ $agencyUser->agencyAccount->member_customers_count }}" disabled>
      </div>
      <div class="form-group{{ $errors->has('max_member_customers') ? ' has-error' : '' }}">
        <label class="control-label">Pachetto Clienti</label>
        <select class="form-control" name="max_member_customers">
          @foreach ($memberCustomersPackages as $package)
            <option value="{{ $package['value'] }}" {{ old('max_member_customers', is_null($agencyUser->agencyAccount->max_member_customers) ? 'unlimited' : $agencyUser->agencyAccount->max_member_customers) == $package['value'] ? 'selected' : '' }}>{{ $package['name'] }}</option>
          @endforeach
        </select>
        <span class="help-block">{{ $errors->first('max_member_customers') }}</span>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-default btn-raised">Modifica</button>
      </div>
    </form>
  </div>
</div>

<br />
<ul class="nav nav-tabs trizzy-color">
  <li class="active"><a href="#customers" data-toggle="tab">Clienti ({{ $agencyUser->agencyAccount->member_customers_count }})</a></li>
  <li><a href="#popups" data-toggle="tab">Popups ({{ $popups->count() }})</a></li>
</ul>
<div id="agency-tabs" class="tab-content">
  <div class="tab-pane fade active in" id="customers">

    <div class="panel panel-primary">
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

  </div>
  <div class="tab-pane fade" id="popups">

    <div class="panel panel-primary">
      <div class="panel-body">
        @include('partials.popup.list', ['popups' => $popups])
      </div>
    </div>

  </div>
</div>


@endsection

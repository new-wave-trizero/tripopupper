@extends('layouts.material')

@section('content')

@can('create-popups')
<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Nuovo Popup</h3>
  </div>
  <div class="panel-body">
    <form method="POST" action="{{ url('/popup') }}" novalidate>
      {{ csrf_field() }}
      <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label">Nome</label>
        <input type="text" class="form-control" name="name" value="{{ old('name', $suggestedName) }}" />
        <span class="help-block">{{ $errors->first('name') }}</span>
      </div>

      <div class="form-group" style="margin-top: 15px">
        <button type="submit" class="btn btn-sm btn-default btn-raised"><i class="material-icons">add_box</i> Nuovo popup</button>
      </div>
    </form>
  </div>
</div>
@endcan

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Popups</h3>
  </div>
  <div class="panel-body">
    @include('partials.popup.list', ['popups' => $popups])
  </div>
</div>
@endsection

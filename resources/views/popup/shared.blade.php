@extends('layouts.material_shared')

@section('subtitle')
  {{ $popup->name }} -
@endsection

@section('content')
<div class="edit-popup-page">

<div class="row">
  <div class="col-md-9">
    <h4>Popup <strong>{{ $popup->name }}</strong></h4>
  </div>
  <div class="col-md-3">
    <div class="btn-group-sm popup-actions-edit">
      <button class="btn btn-success btn-fab btn-fab-mini" id="popup-launcher" title="Prova popup {{ $popup->name }}!">
        <i class="material-icons">launch</i></button>
    </div>
  </div>
</div>

<br />

<form method="POST" action="{{ url('/popup/' . $popup->name) }}" id="popup-form" novalidate>
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <div class="panel panel-primary">

    <div class="panel-heading trizzy-color">
      <h3 class="panel-title">Configura</h3>
    </div>
    <div class="panel-body">

      <div
        id="popup-config-editor"
        data-popup='{!! json_encode(array_except($popup->toArray(), 'config')) !!}'
        data-json='{!! old('config', json_encode($popup->config)) !!}'></div>

    </div>
  </div>
</form>

</div>
@endsection

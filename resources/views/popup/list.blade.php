@extends('layouts.material')

@section('content')

@can('create-popups')
<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Nuovo Popup</h3>
  </div>
  <div class="panel-body">
    @include('partials.popup.create_form', ['action' => url('popup'), 'suggest_name' => true ])
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

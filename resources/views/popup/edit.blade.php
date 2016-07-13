@extends('layouts.material')

@section('content')
<div class="edit-popup">
<div class="row">
  <div class="col-md-10">
    <h4>Modifica popup <strong>{{ $popup->name }}</strong></h4>
  </div>
  <div class="col-md-2" style="text-align: right">
    <div class="btn-group-sm">
      <a href="/" class="btn btn-fab btn-fab-mini"><i class="material-icons">list</i></a>
      <button class="btn btn-success btn-fab btn-fab-mini" id="popup-launcher"><i class="material-icons">launch</i></button>
      <form style="display:inline" method="POST" action="/popup/{{ $popup->name }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button class="btn btn-danger btn-fab btn-fab-mini"><i class="material-icons">delete</i></button>
      </form>
    </div>
  </div>
</div>
<br />


<div>Includi questo codice alla fine della tua pagina web:</div>
<pre style="border-radius: 0"><code>{{ "<script src=\"".config('popup.js_lib_url')."\"></script>\n<script>tripopupper.launch('".$popup->name."')</script>" }}</pre></code>


<form method="POST" action="/popup/{{ $popup->name }}" id="popup-form" novalidate>
  {{ csrf_field() }}
  {{ method_field('PUT') }}

  <div class="form-group{{ $errors->has('domain') ? ' has-error' : '' }}">
    <label class="control-label">Domain</label>
    <input name="domain" type="text" class="form-control" value="{{ old('domain', $popup->domain) }}">
    <span class="help-block">{{ $errors->first('domain') }}</span>
  </div>

  <div id="popup-config-editor" data-json='{!! old('config', json_encode($popup->config)) !!}'></div>

  <button type="submit" class="btn btn-default btn-raised">Salva</button>
</form>
</div>
@endsection

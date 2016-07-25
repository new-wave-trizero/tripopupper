@extends('layouts.material')

@section('subtitle')
  {{ $popup->name }} -
@endsection

@section('content')
<div class="edit-popup-page">
<div class="row">
  <div class="col-md-10">
    <h4>Modifica popup <strong>{{ $popup->name }}</strong></h4>
  </div>
  <div class="col-md-2" style="text-align: right">
    <div class="btn-group-sm">
      <a href="{{ url('/') }}" class="btn btn-fab btn-fab-mini"><i class="material-icons">list</i></a>
      <button class="btn btn-success btn-fab btn-fab-mini" id="popup-launcher"><i class="material-icons">launch</i></button>
      <a href="{{ url('/api/popup/' . $popup->name) }}"
        class="btn btn-primary btn-fab btn-fab-mini" target="blank">
        <i class="material-icons">code</i>
      </a>
      <form style="display:inline" method="POST" action="{{ url('/popup/' . $popup->name) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button class="btn btn-danger btn-fab btn-fab-mini"><i class="material-icons">delete</i></button>
      </form>
    </div>
  </div>
</div>
<br />

<div>Includi questo codice alla fine della tua pagina web, prima della fine del tag <code>{{ '</body>' }}</code>:</div>
<div id="popup-embedded-snippet">
<pre style="border-radius: 0" data-debug="0"><code>{{ "<script src=\"".config('popup.js_lib_url')."\"></script>\n<script>tripopupper.launch('".$popup->name."')</script>" }}</code></pre>
<pre style="border-radius: 0;display:none" data-debug="1"><code>{{ "<script src=\"".config('popup.js_lib_url')."\"></script>\n<script>tripopupper.launch('".$popup->name."', true)</script>" }}</code></pre>
</div>
<div class="togglebutton">
  <label>
    <input type="checkbox" id="popup-snippet-debug-mode-toggle"> Debug Mode
  </label>
</div>
<button
  class="btn btn-raised btn-default btn-clipboard"
  data-clipboard-target="#popup-embedded-snippet"> <i class="material-icons">content_copy</i>  Copia</button>
<br />
<br />

<form method="POST" action="{{ url('/popup/' . $popup->name) }}" id="popup-form" novalidate>
  <div class="well well-sm">
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <h3>Configura Dominio</h3>
  <div class="well well-sm">
    <div class="form-group{{ $errors->has('domain') ? ' has-error' : '' }}">
      <label class="control-label">Dominio</label>
      <input name="domain" type="text" class="form-control" value="{{ old('domain', $popup->domain) }}">
      <span class="help-block">{{ $errors->first('domain') }}</span>
    </div>
  </div>

  <div
    id="popup-config-editor"
    data-popup='{!! json_encode(array_except($popup->toArray(), 'config')) !!}'
    data-json='{!! old('config', json_encode($popup->config)) !!}'></div>

  </div>
  <button type="submit" class="btn btn-default btn-raised">Salva</button>
</form>
</div>
@endsection

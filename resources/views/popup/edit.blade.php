@extends('layouts.material')

@section('subtitle')
  {{ $popup->name }} -
@endsection

@section('content')
<div class="edit-popup-page">

<div class="row">
  <div class="col-md-9">
    <h4>Modifica popup <strong>{{ $popup->name }}</strong></h4>
  </div>
  <div class="col-md-3">
    <div class="btn-group-sm popup-actions-edit">
      <a href="{{ url('/') }}" class="btn btn-fab btn-fab-mini" title="Elenco dei popup">
        <i class="material-icons">list</i></a>
        <button class="btn btn-success btn-fab btn-fab-mini" id="popup-launcher" title="Prova popup {{ $popup->name }}!">
        <i class="material-icons">launch</i></button>
      <a href="{{ url('/api/popup/' . $popup->name) }}"
         title="Configurazione popup {{ $popup->name }}"
         class="btn btn-primary btn-fab btn-fab-mini" target="blank">
        <i class="material-icons">code</i>
      </a>
      <form style="display:inline" method="POST" action="{{ url('/popup/' . $popup->name) }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button
           class="btn btn-danger btn-fab btn-fab-mini ask-confirm"
           data-confirm-title="Popup {{ $popup->name }}"
           data-confirm-body="Sei sicuro di voler eliminare il popup <strong>{{ $popup->name }}</strong>?"
           data-confirm-btn-title="Elimina"
           data-confirm-btn-class="btn-danger"
           title="Elimina popup {{ $popup->name }}">
          <i class="material-icons">delete</i></button>
      </form>
    </div>
  </div>
</div>

<br />

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Integrazione</h3>
  </div>
  <div class="panel-body">
    <p>Includi questo codice alla fine della tua pagina web, prima della fine del tag <code>{{ '</body>' }}</code>:</p>
    <div id="popup-embedded-snippet">
      <pre style="border-radius: 0" data-debug="0"><code>{{ "<script src=\"".config('popup.js_lib_url')."\"></script>\n<script>tripopupper.launch('".$popup->name."')</script>" }}</code></pre>
      <pre style="border-radius: 0;display:none" data-debug="1"><code>{{ "<script src=\"".config('popup.js_lib_url')."\"></script>\n<script>tripopupper.launch('".$popup->name."', true)</script>" }}</code></pre>
    </div>
    <div class="togglebutton">
      <label>
        <input type="checkbox" id="popup-snippet-debug-mode-toggle"> Modalità Debug
      </label>
    </div>
    <button
      class="btn btn-sm btn-raised btn-default btn-clipboard"
      data-clipboard-target="#popup-embedded-snippet"> <i class="material-icons">content_copy</i>  Copia</button>
  </div>
</div>

<form method="POST" action="{{ url('/popup/' . $popup->name) }}" id="popup-form" novalidate>
  {{ csrf_field() }}
  {{ method_field('PUT') }}
  <div class="panel panel-primary">

    <div class="panel-heading trizzy-color">
      <h3 class="panel-title">Configura</h3>
    </div>
    <div class="panel-body">

      <div class="panel panel-primary">
        <div class="panel-heading trizzy-color">
          <h3 class="panel-title">Dominio</h3>
        </div>
        <div class="panel-body">
          <div class="form-group{{ $errors->has('domain') ? ' has-error' : '' }}">
            <label class="control-label">Dominio</label>
            <input name="domain" type="text" class="form-control" value="{{ old('domain', $popup->domain) }}">
            <span class="help-block">{{ $errors->first('domain') }}</span>
          </div>
        </div>
      </div>

      <div
        id="popup-config-editor"
        data-popup='{!! json_encode(array_except($popup->toArray(), 'config')) !!}'
        data-json='{!! old('config', json_encode($popup->config)) !!}'></div>

      <button type="submit" class="btn btn-sm btn-default btn-raised">Salva</button>

    </div>
  </div>
</form>

</div>
@endsection

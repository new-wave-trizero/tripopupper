@extends('layouts.material')

@section('content')
<div class="popups-composition-page">

<div class="panel panel-primary">
  <div class="panel-heading trizzy-color">
    <h3 class="panel-title">Integrazione</h3>
  </div>
  <div class="panel-body">
    <p>Trascina e riordina i popup dalla casella 'Popups' alla casella 'Composizione' per assemblare la tua composizione di popup!</p>
    <p>Includi questo codice alla fine della tua pagina web, prima della fine del tag <code>{{ '</body>' }}</code>:</p>
    <div>
      <pre style="border-radius: 0" data-debug="0"><code id="popup-embedded-snippet"></code></pre>
    </div>
    <div class="togglebutton">
      <label>
        <input type="checkbox" id="popup-snippet-debug-mode-toggle"> Modalità Debug
      </label>
    </div>
    <button
      class="btn btn-sm btn-raised btn-default btn-clipboard"
      data-clipboard-target="#popup-embedded-snippet"> <i class="material-icons">content_copy</i>  Copia</button>
    <button
      id="launch-composition-btn"
      class="btn btn-sm btn-raised btn-success"> <i class="material-icons">launch</i>  Prova!</button>
  </div>
</div>

<div class="row">

  <div class="col-md-6">
  <div class="panel panel-primary">
    <div class="panel-heading trizzy-color">
      <h3 class="panel-title">Composizione</h3>
    </div>
    <div class="panel-body popups-drag-container" id="popups-group-composition">
    </div>
  </div>
  </div>

  <div class="col-md-6">
  <div class="panel panel-primary">
    <div class="panel-heading trizzy-color">
      <h3 class="panel-title">Popups</h3>
    </div>
    <div class="panel-body popups-drag-container" id="popups-group-bag">
      @foreach ($popups as $popup)
        @if (!isset($popup->config['imageUrl']) || empty($popup->config['imageUrl']))
          <div class="popup-bubble" data-id="{{ $popup->name }}">
            <i class="material-icons" title="{{ $popup->name }}">insert_photo</i>
          </div>
        @else
          <div class="popup-bubble" data-id="{{ $popup->name }}">
            <img src="{{ $popup->config['imageUrl'] }}" title="{{ $popup->name }}">
          </div>
        @endif
      @endforeach
    </div>
  </div>
  </div>

</div>

</div>
@endsection

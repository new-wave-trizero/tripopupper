<div class="list-group">
@foreach($popups as $popup)
  <div class="list-group-item">
    @if (!isset($popup->config['imageUrl']) || empty($popup->config['imageUrl']))
      <div class="row-action-primary">
        <i class="material-icons">insert_photo</i>
      </div>
    @else
      <div class="row-picture">
        <img class="circle" src="{{ $popup->config['imageUrl'] }}" />
      </div>
    @endif
    <div class="row-content">
      <h4 class="list-group-item-heading">{{ $popup->name }}</h4>
      <p class="list-group-item-text">
        {{ $popup->domain }}&nbsp;
      </p>
      <div class="action-secondary popup-actions-list">
        <div class="btn-group-sm">
          <a href="{{ url('/popup/' . $popup->name) }}"
             class="btn btn-info btn-fab btn-fab-mini"
             title="Modifica popup {{ $popup->name }}">
            <i class="material-icons">mode_edit</i>
          </a>
          <button
            title="Prova popup {{ $popup->name }}!"
            class="btn btn-success btn-fab btn-fab-mini inline-popup-launcher"
            data-config='{!! json_encode($popup->config) !!}'>
            <i class="material-icons">launch</i>
          </button>
          <a href="{{ url('/api/popup/' . $popup->name) }}"
             title="Configurazione popup {{ $popup->name }}"
             class="btn btn-primary btn-fab btn-fab-mini" target="blank">
            <i class="material-icons">code</i>
          </a>
          @can('delete-popup', $popup)
            <form style="display:inline" method="POST" action="{{ url('/popup/'. $popup->name) }}">
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
          @endcan
        </div>
      </div>
    </div>
  </div>
  <div class="list-group-separator"></div>
@endforeach
</div>

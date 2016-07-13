@extends('layouts.material')

@section('content')

<form method="POST" action="/popup" novalidate>
  {{ csrf_field() }}
  <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="control-label">Name o random</label>
    <input type="text" class="form-control" name="name" value="{{ old('name') }}" />
    <span class="help-block">{{ $errors->first('name') }}</span>
  </div>

  <div class="form-group">
  <button type="submit" class="btn btn-default btn-raised">
    Crea poppup
  </button>
  </div>
</form>

<br />
<br />
<br />

<div class="list-group">
  @foreach($popups as $popup)
    <div class="list-group-item">
      <div class="row-picture">
        <img class="circle" alt="icon">
      </div>
      <div class="row-content">
        <div class="action-secondary">
          <div class="btn-group-sm">
            <button
              class="btn btn-success btn-fab btn-fab-mini inline-popup-launcher"
              data-config='{!! json_encode($popup->config) !!}'>
              <i class="material-icons">launch</i>
            </button>
            <form style="display:inline" method="POST" action="/popup/{{ $popup->name }}">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button class="btn btn-danger btn-fab btn-fab-mini"><i class="material-icons">delete</i></button>
            </form>
          </div>
        </div>
        <a class="btn btn-link-list-item" href="/popup/{{ $popup->name }}">
        <h4 class="list-group-item-heading">{{ $popup->name }}</h4>
        </a>
        <p class="list-group-item-text">
          <div>{{ $popup->domain }}&nbsp;</div>
        </p>
      </div>
    </div>
    <div class="list-group-separator"></div>
  @endforeach
</div>
@endsection

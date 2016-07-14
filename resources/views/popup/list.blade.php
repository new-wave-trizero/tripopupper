@extends('layouts.material')

@section('content')

<form method="POST" action="{{ url('/popup') }}" novalidate>
  {{ csrf_field() }}
  <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
    <label class="control-label">Name</label>
    <input type="text" class="form-control" name="name" value="{{ old('name', $suggestedName) }}" />
    <span class="help-block">{{ $errors->first('name') }}</span>
  </div>

  <div class="form-group" style="margin-top: 15px">
  <button type="submit" class="btn btn-default btn-raised">
    Nuovo popup
  </button>
  </div>
</form>

<br />
<br />

<div class="list-group">
  @foreach($popups as $popup)
    <div class="list-group-item">
      <div class="row-picture">
        <img class="circle" src="{{ $popup->config['imageUrl'] or null }}">
      </div>
      <div class="row-content">
        <div class="action-secondary">
          <div class="btn-group-sm">
            <a href="{{ url('/popup/' . $popup->name) }}" class="btn btn-info btn-fab btn-fab-mini">
              <i class="material-icons">mode_edit</i>
            </a>
            <button
              class="btn btn-success btn-fab btn-fab-mini inline-popup-launcher"
              data-config='{!! json_encode($popup->config) !!}'>
              <i class="material-icons">launch</i>
            </button>
            <form style="display:inline" method="POST" action="{{ url('/popup/'. $popup->name) }}">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button class="btn btn-danger btn-fab btn-fab-mini"><i class="material-icons">delete</i></button>
            </form>
          </div>
        </div>
        <h4 class="list-group-item-heading">{{ $popup->name }}</h4>
        <p class="list-group-item-text">
          <div>{{ $popup->domain }}&nbsp;</div>
        </p>
      </div>
    </div>
    <div class="list-group-separator"></div>
  @endforeach
</div>
@endsection

<span
   class="new-popup-form-container"
   data-name="{{ old('name', $suggestedName) }}"
   data-name-error="{{ $errors->first('name') }}"
   data-action="{{ $action }}"></span>

{{--  <form method="POST" action="{{ $action }}" novalidate>
    {{ csrf_field() }}
    <div class="form-group label-floating{{ $errors->has('name') ? ' has-error' : '' }}">
      <label class="control-label">Nome</label>
      <input type="text" class="form-control" name="name" value="{{ old('name', $suggestedName) }}" />
      <span class="help-block">{{ $errors->first('name') }}</span>
    </div>

    <div class="form-group" style="margin-top: 15px">
      <button type="submit" class="btn btn-sm btn-default btn-raised"><i class="material-icons">add_box</i> Nuovo popup</button>
    </div>
  </form> --}}

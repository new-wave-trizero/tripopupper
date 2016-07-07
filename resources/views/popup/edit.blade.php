@extends('layouts.material')

@section('content')
<div class="row">
  <div class="col-md-10">
    <h4>Edit popup <strong>{{ $popup->name }}</strong></h4>
  </div>
  <div class="col-md-2" style="text-align: right">
    <div class="btn-group-sm">
      <a href="/" class="btn btn-fab btn-fab-mini"><i class="material-icons">list</i></a>
      <form style="display:inline" method="POST" action="/popup/{{ $popup->name }}">
        <input type="hidden" name="_method" value="DELETE" />
        <button class="btn btn-danger btn-fab btn-fab-mini"><i class="material-icons">delete</i></button>
      </form>
    </div>
  </div>
</div>

<form method="POST" action="/popup" novalidate>
   <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
  </div>
  <button type="submit" class="btn btn-default btn-raised">Salva</button>
</form>

@endsection

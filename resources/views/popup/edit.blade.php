@extends('layouts.minimal')

@section('content')
<div><a href="/">list</a></div>
<br />

<div>Edit popup <strong>{{ $popup->name }}</strong></div>
{{ $errors->first('name') }}

<form method="POST" action="/popup" novalidate>

  <p>
    <input type="text" name="domain" placeholder="Domain"></input>
    <div>{{ $errors->first('domain') }}</div>
  </p>
  <p>
    <textarea name="config" placeholder="Config"></textarea>
    <div>{{ $errors->first('config') }}</div>
  </p>
  <input type="submit" value="Salva" />
</form>
@endsection

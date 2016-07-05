
<div><a href="/popup">list</a></div>
<br />

@if ($popup)
  <form method="PUT" action="/popup/{{ $name }}" novalidate>
@else
  <form method="POST" action="/popup" novalidate>
@endif

<div>Hey this is <strong>{{ $name }}</strong></div>
{{ $errors->first('name') }}

<p>
<input type="text" name="domain" placeholder="Domain"></input>
</p>
<p>
  <textarea name="config" placeholder="Config"></textarea>
</p>
<input type="submit" value="Salva" />
<input type="hidden" name="name" value="{{ $name }}" />
</form>

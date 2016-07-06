@extends('layouts.minimal')

@section('content')
<form method="POST" action="/popup" novalidate>
  <input type="text" name="name" placeholder="Name or random" value="{{ old('name') }}" />
  <input type="submit" value="+"/>
  <div style="color:#F4645F;padding: 5px 0px">{{ $errors->first('name') }}</div>
</form>

<ul>
  @foreach($popups as $popup)
    <li>
      <a href="/popup/{{ $popup->name }}">{{ $popup->name }}</a>
      <form method="POST" action="/popup/{{ $popup->name }}" style="display:inline">
        <input type="hidden" name="_method" value="DELETE" />
        <input type="submit" value="x" />
      </form>
    </li>
  @endforeach
</ul>
@endsection

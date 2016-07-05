
<div><a href="/popup/suggest">+</a></div>

<ul>
@foreach($popups as $popup)
  <li><a href="/popup/{{ $popup->name }}">{{ $popup->name }}</a></li>
@endforeach
</ul>

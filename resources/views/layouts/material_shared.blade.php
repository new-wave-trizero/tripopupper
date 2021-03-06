<!DOCTYPE html>
<html>
<head>
  <title>@yield('subtitle')Tripopupper</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Mobile support -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Material Design fonts -->
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link href="{{ asset('/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Bootstrap Material Design -->
  <link href="{{ asset('/material/css/bootstrap-material-design.css') }}" rel="stylesheet">
  <link href="{{ asset('/material/css/ripples.min.css') }}" rel="stylesheet">

  <link href="{{ asset('/css/tripopupper.css') }}" rel="stylesheet">

  <!-- jQuery -->
  <script src="{{ asset('/js/jquery.min.js') }}"></script>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top trizzy-color">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ url('/') }}">Tripopupper</a>
    </div>
    <div class="navbar-collapse collapse navbar-inverse-collapse">
    </div>
  </div>
</div>

<div class="container-fluid">
  @yield('content')
</div>

<script>
  window.laravel = {!! json_encode($laravelJsVars) !!};
</script>
<!-- Twitter Bootstrap -->
<script src="{{ asset('/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- Material Design for Bootstrap -->
<script src="{{ asset('/material/js/material.min.js') }}"></script>
<script src="{{ asset('/material/js/ripples.min.js') }}"></script>
<script>
  $.material.init();
</script>

<script src="{{ asset('/js/tripopupper.js') }}"></script>
<script src="{{ config('popup.js_lib_url') }}"></script>

</body>
</html>

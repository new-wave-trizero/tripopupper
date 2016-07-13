<!DOCTYPE html>
<html>
<head>
  <title>Tri-Pop-Upper!</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Mobile support -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Material Design fonts -->
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Material Design -->
  <link href="/material/css/bootstrap-material-design.css" rel="stylesheet">
  <link href="/material/css/ripples.min.css" rel="stylesheet">

  <link href="/css/tripopupper.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="/js/jquery.min.js"></script>

</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">Tripopupper</a>
    </div>
</nav>

<div class="container-fluid">
  @yield('content')
</div>

<!-- Twitter Bootstrap -->
<script src="/bootstrap/js/bootstrap.min.js"></script>

<!-- Material Design for Bootstrap -->
<script src="/material/js/material.js"></script>
<script src="/material/js/ripples.min.js"></script>
<script>
  $.material.init();
</script>

<script src="/js/tripopupper.js"></script>
<script src="{{ config('popup.js_lib_url') }}"></script>

</body>
</html>

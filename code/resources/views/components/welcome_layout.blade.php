<html>
  <head>
    <title>{{ $title ?? 'SWC News' }}</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
  </head>
  <body>
    <header>
      <h2>SWC News</h2>
      <hr>
    </header>
    {{ $slot }}
    <footer>
      <hr />
      © SWC News
    </footer>
  </body>
</html>
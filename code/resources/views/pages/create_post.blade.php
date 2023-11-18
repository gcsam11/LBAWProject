<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Create Post</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">

        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <header>
            <h3>SWC News</h3>
            <hr>
        </header>
        <main>
            <h1>Publish New Post</h1>
            
            <div id="content">
              <form method="POST" action="{{ route('create_post') }}">
                {{ csrf_field() }}

                <label for="title">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus>
                @if ($errors->has('title'))
                  <span class="error">
                    {{ $errors->first('title') }}
                  </span>
                @endif

                <label for="content">Content</label>
                <textarea id="content" type="text" name="content" required></textarea>
                @if ($errors->has('content'))
                  <span class="error">
                    {{ $errors->first('content') }}
                  </span>
                @endif

                <button type="submit">
                  Publish
                </button>
              </form>
            </div>
        </main>
        <footer>
            <hr/>
            © SWC News
        </footer>
    </body>
</html>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://kit.fontawesome.com/f1d77e88ed.js" crossorigin="anonymous"></script>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>@yield('title')</title>

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
            <h1>@yield('header')</h1>
            <nav>
                
                @guest
                <a href="{{ route('posts.top') }}">News Feed</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('login') }}">Login</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('register') }}">Register</a>
                @endguest
                
                @auth
                <a href="{{ route('posts.top') }}">News Feed</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('user_news') }}">User News</a>&nbsp;&nbsp;&nbsp;
                <a href="{{ route('profile_page', ['id' => Auth::id()]) }}">Profile</a>&nbsp;&nbsp;&nbsp;

                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin_dashboard') }}">Admin Dashboard</a>&nbsp;&nbsp;&nbsp;
                @endif
                
                @endauth

            </nav>
            <br>
            <div id="content">
                @yield('content')
            </div>
        </main>
        <footer>
            <hr/>
            © SWC News
        </footer>
    </body>
</html>
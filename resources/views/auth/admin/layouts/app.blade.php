<!DOCTYPE html>
<html lang="en">
    <head>
        @include('auth.admin.layouts.head')
    </head>


    <body class="authentication-bg">
        @yield('content')

        @include('auth.admin.layouts.js')
    </body>
</html>

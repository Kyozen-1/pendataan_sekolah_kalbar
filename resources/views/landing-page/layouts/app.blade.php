<!DOCTYPE html>
<html lang="en">
    <head>
        @include('landing-page.layouts.head')
    </head>

    <body>

        <!--Navbar Start-->
        @include('landing-page.layouts.navbar')
        <!-- Navbar End -->

        @yield('content')

        <!-- footer start -->
        {{-- @include('landing-page.layouts.footer') --}}
        <!-- footer end -->

        <!-- Back to top -->
        <a href="#" class="back-to-top" id="back-to-top"> <i class="mdi mdi-chevron-up"> </i> </a>


        <!-- javascript -->
        @include('landing-page.layouts.js')
    </body>

</html>

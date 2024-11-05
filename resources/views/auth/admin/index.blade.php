@extends('auth.admin.layouts.app')

@section('content')
<div class="account-pages mt-3 mb-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="text-center">
                    <a href="{{ route('home') }}">
                        <span><img src="{{asset('/images/logos/siaplah-01.png')}}" alt="Logo SIAPLAH KALBAR" style="height: 10rem;"></span>
                    </a>
                    <p class="text-muted mt-0 mb-3">Login ADMIN</p>
                </div>
                <div class="card">

                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h4 class="text-uppercase mt-0">Login</h4>
                        </div>

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                </button>
                                <strong>Error: </strong> {{ $error }}
                            </div>
                            @endforeach
                        @endif

                        <form action="{{ route('admin.login') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group mb-3">
                                <label for="emailaddress">Email address</label>
                                <input class="form-control" type="email" id="emailaddress" required="" name="email" placeholder="Enter your email">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" required="" name="password" id="password" placeholder="Enter your password">
                                <i class="far fa-eye field-icon toggle-password" toggle="#password-field" style="float: right; margin-left: -25px; margin-top: -25px; position: relative; z-index: 2; margin-right:5px;"></i>
                            </div>

                            {!! NoCaptcha::display() !!}

                            <div class="form-group mt-3 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="checkbox-signin" checked>
                                    <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                            </div>

                        </form>

                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
@endsection

@section('js')
{!! NoCaptcha::renderJs() !!}
@endsection

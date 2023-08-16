<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | Minton - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('images/favicon.png')}}" />

    <!-- App css -->
    <link href="{{ url('css/default/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ url('css/default/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

    <link href="{{ url('css/default/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-dark-stylesheet" />
    <link href="{{ url('css/default/app-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="app-dark-stylesheet" />

    <!-- icons -->
    <link href="{{ url('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="loading">
    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="index.html" class="logo logo-dark text-center">
                                        <span class="">
                                            <img src="{{ url('images/makeajodi.png') }}" width="100%" height="auto" alt="" height="22" />
                                        </span>
                                    </a>

                                    <a href="index.html" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img src="{{ url('images/makeajodi.png') }}" alt="" height="22" />
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">
                                    Enter your email address and password to access admin panel.
                                </p>
                            </div>

                            <form action="{{ route('adminlogin') }}" id="loginform" method="post" autocomplete="off">
                                @csrf
                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Email address</label>
                                    @csrf
                                    <input class="form-control" type="email" name="email" id="emailaddress" required=""
                                        placeholder="Enter your email" />
                                </div>

                                <div class="mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Enter your password" />

                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 form_output"></div>
                                <div class="d-grid mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">
                                        Log In
                                    </button>
                                    @if (!empty(Auth::user()))
                                        <input type="hidden" value="{{Auth::user()->id}}" id="loggedIn">
                                    @endif
                                </div>
                            </form>
                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <script>
            document.write(new Date().getFullYear());
        </script>
        &copy; HamsMatrimony <a href="#" class="text-dark">HansMatrimony</a>
    </footer>

    <!-- Vendor js -->
    <script src="{{ url('js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ url('js/app.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            if($('#loggedIn').val()>0){
                window.location="{{route('dashboard')}}";
            }
            $(document).on('submit', '#loginform', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(login_response) {
                        if(login_response.type==true){
                            message_html = `<div class="alert alert-success" role="alert">
                                                <strong>${login_response.message}</strong> Wait for Redirection
                                            </div>`;
                            window.setTimeout(function(){
                               window.location="{{route('userdashboard')}}";
                            },2500);
                        }else{
                            message_html = `<div class="alert alert-danger" role="alert">
                                                <strong>${login_response.message}</strong>
                                            </div>`;
                        }
                        $('.form_output').html(message_html);
                        window.setTimeout(() => {
                            $('.form_output').html('');
                        }, 2000);
                    }
                });
            });
        });
    </script>
</body>
</html>


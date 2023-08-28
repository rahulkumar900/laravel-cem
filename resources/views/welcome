<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Log In | MakeAJodi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('images/favicon1.ico')}}" />

    <!-- App css -->
    <link href="{{ url('css/default/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
        id="bs-default-stylesheet" />
    <link href="{{ url('css/default/app.min.css') }}" rel="stylesheet" type="text/css"
        id="app-default-stylesheet" />

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
                            <div class="text-center m-auto">
                                <div class="auth-logo">
                                    <a href="index.html" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <img class="w-100" src="{{ url('images/makeajodi.png') }}" alt=""/>
                                        </span>
                                    </a>

                                    <a href="index.html" class="logo logo-light text-center">
                                        <span class="logo-lg">
                                            <img class="w-100" src="{{ url('images/logo-dark.png') }}" alt="" />
                                        </span>
                                    </a>
                                </div>
                                <p class="text-muted mb-4 mt-3">
                                    Enter your Mobile Number and OTP to Access.
                                </p>
                                <div class="form_output"></div>
                            </div>

                            <form action="{{ route('verifyuserotp') }}" id="loginform" method="post" autocomplete="off">
                                @csrf
                                <div class="mb-2">
                                    <label for="emailaddress" class="form-label">Mobile Number</label>
                                    <input class="form-control" type="number" id="mobile_number" name="mobile_number"
                                        required="" placeholder="Enter your Mobile" maxlength="10" />
                                </div>

                                <div class="mb-2 otp_div" style="display: none">
                                    <label for="password" class="form-label">OTP</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="received_otp" name="received_otp" class="form-control"
                                            placeholder="Enter Received OTP" />
                                    </div>
                                </div>

                                <div class="d-grid mb-0 text-center">
                                    <button type="button" class="btn btn-primary otp_button">Get OTP</button>
                                    <button class="btn btn-primary submit_otp" type="submit" style="display: none">
                                        Log In
                                    </button>
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
            // $(document).on('keyup','#mobile_number',function(){
            //     alert($(this).val());
            // });

            // submit login btn
            $(document).on('submit', '#loginform', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(loginResponse) {
                        if (loginResponse.type == 'success') {
                            localStorage.setItem('security_key',loginResponse.token2);
                            window.location = "{{route('userdashboard')}}";
                            sessionStorage.setItem("checkin_status", true);
                            localStorage.setItem("timeRemain", 1800);
                        } else {
                            $('.form_output').html(
                                '<div class="alert alert-danger" role="alert"><strong>OTP Verification</strong> Failed</div>'
                            );
                        }
                    }
                });
            });

            // get otp
            $(document).on('click', '.otp_button', function(e) {
                e.preventDefault();
                var mobile_number = $('#mobile_number').val();
                if (mobile_number.length != 10) {
                    $('.form_output').html(
                        '<div class="alert alert-danger" role="alert">Mobile Number Should Have <strong>10 Diigits</strong> to Get OTP</div>'
                    );
                } else {
                    var token = $('input[name="csrfToken"]').attr('value');

                    $.ajax({
                        type: "post",
                        url: "{{ route('loginusers') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'mobile': mobile_number
                        },
                        success: function(sendOtpResponse) {
                            if (sendOtpResponse.status == true) {
                                $('.otp_button').hide();
                                $('.submit_otp').show();
                                $('.otp_div').show();
                                $('#mobile_number').prop('readonly', true);
                                $('.otp_button').prop('readonly', true);
                                $('.form_output').html(
                                    '<div class="alert alert-success" role="alert">Success <strong>'+sendOtpResponse.message+'</strong></div>'
                                );
                            } else {
                                $('.form_output').html(
                                    '<div class="alert alert-danger" role="alert">Failed To Send <strong>OTP</strong></div>'
                                );
                            }
                        }
                    });
                }
                window.setTimeout(() => {
                    $('.form_output').html('');
                }, 3000);
            });

             localStorage.clear();
        });
    </script>
</body>

</html>

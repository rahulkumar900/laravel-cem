<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Profile PDf Creation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style rel="stylesheet" href="{{ url('libs/pdfmake/build/pdfmake.min.js') }}"></style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css"
        integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container pdfDiv" id="pdf_data">
        <div class="row shadow" style="height: 99vh;  page-break-after: always;">
            <div class="col-md-12 text-center">
                <img src="{{ url('images/hans_logo.png') }}" class="main-banner-img mt-3 w-75" alt="">
                <h4 class="d-none">Twango Social Network Pvt. Ltd.</h4>
                <hr>
                <div class="row mt-5 shadow-sm text-center">
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5> Website: www.hansmatrimony.com</h5>
                    </div>
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5>Mail us at : info@hansmatrimony.com</h5>
                    </div>
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5>Add : H-18 Bali Nagar, New Delhi</h5>
                    </div>
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5>Contact : +91 969 798 9697</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center mt-2">
                <img src="{{ url('images/1e1ef807efe66d39bc6b9402d0f1d62b_collage_450.jpg') }}" alt=""
                    class="img-thumbnail rounded" style="height: 650px; width: auto">
                <h5 class="mt-5 d-none">Dear Shubham Bhatia Please Find the Attached Profiles As Per Your Requirement
                </h5>
            </div>
            <div class="col-md-12 border-bottom border-top m-5" style="height: 80px;">
                <div class="row pt-3">
                    <div class="col-sm-2 text-center">
                        <a href="https://www.facebook.com/HansMatrimony" class="btn"><i class="fa fa-2x fa-facebook"
                                aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://www.instagram.com/hansmatrimony/" class="btn"><i
                                class="fa fa-2x fa-instagram" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://www.linkedin.com/company/hansmatrimony/" class="btn"><i
                                class="fa fa-2x fa-linkedin" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://www.youtube.com/channel/UCXeEAxOuoMoBCcu45PEtfmg/featured" class="btn"><i
                                class="fa fa-2x fa-youtube" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://twitter.com/HansMatrimony" class="btn"><i class="fa fa-2x fa-twitter"
                                aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://g.page/HansMatrimony?share" class="btn"><i class="fa fa-2x fa-map-marker"
                                aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="row mt-5">
                <h4 class="text-primary mb-3 mt-3 text-left">Personal Details</h4>
            </div>
            <div class="row">
                <div class="col-4">
                    <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $user_detail_by_id->photo }}"
                        height="250px" width="250px">
                </div>
                <div class="col-8">
                    <div class="row">
                        <div class="col-12">
                            <h5><b>Name</b></h5>:{{ $user_detail_by_id->name }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><b>Gender</b></h5>:{{ $user_detail_by_id->gender }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><b>DOB</b></h5>:{{ date('d/m/Y', strtotime($user_detail_by_id->birth_date)) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h4 class="text-primary mb-3 mt-3 text-left">Sent Profiles</h4>
            </div>
            <div class="row">
                @if ($sent_profile_list ?? '')
                    @foreach ($sent_profile_list ?? '' as $Data)
                        <div class="col-3">
                            <div class="card product-box">
                                <div class="product-img">
                                    <div class="p-3 text-center">
                                        <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $Data->photo }}"
                                            alt="profile-pic" height="150px" width="150px">
                                    </div>
                                </div>
                                <div class="product-info border-top p-3">
                                    <div>
                                        <h5> Name : {{ $Data->name }}</h5>
                                        <h6> Marital Status : {{ $Data->marital_status }}</h6>
                                        <h6> Caste : {{ $Data->caste }}</h6>
                                        <h6> Profile Sent Date : {{ date('d/m/Y', strtotime($Data->created_at)) }}</h6>
                                    </div>
                                </div> <!-- end product info-->
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <h4 class="text-primary mb-3 mt-3 text-left">Liked By Me Profiles</h4>
            </div>
            <div class="row">
                @if ($liked_by_me_profile_list ?? '')
                    @foreach ($liked_by_me_profile_list ?? '' as $Data)
                        <div class="col-3">
                            <div class="card product-box">
                                <div class="product-img">
                                    <div class="p-3 text-center">
                                        <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $Data->photo }}"
                                            alt="profile-pic" height="150px" width="150px">
                                    </div>
                                </div>
                                <div class="product-info border-top p-3">
                                    <div>
                                        <h5> Name : {{ $Data->name }}</h5>
                                        <h6> Marital Status : {{ $Data->marital_status }}</h6>
                                        <h6> Caste : {{ $Data->caste }}</h6>
                                        <h6> Profile Sent Date : {{ date('d/m/Y', strtotime($Data->created_at)) }}</h6>
                                    </div>
                                </div> <!-- end product info-->
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <h4 class="text-primary mb-3 mt-3 text-left">Rejected By Me Profiles</h4>
            </div>
            <div class="row">
                @if ($rejected_by_me_profile_list ?? '')
                    @foreach ($rejected_by_me_profile_list ?? '' as $Data)
                        <div class="col-3">
                            <div class="card product-box">
                                <div class="product-img">
                                    <div class="p-3 text-center">
                                        <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $Data->photo }}"
                                            alt="profile-pic" height="150px" width="150px">
                                    </div>
                                </div>
                                <div class="product-info border-top p-3">
                                    <div>
                                        <h5> Name : {{ $Data->name }}</h5>
                                        <h6> Marital Status : {{ $Data->marital_status }}</h6>
                                        <h6> Caste : {{ $Data->caste }}</h6>
                                        <h6> Profile Sent Date : {{ date('d/m/Y', strtotime($Data->created_at)) }}</h6>
                                    </div>
                                </div> <!-- end product info-->
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="row">
                <h4 class="text-primary mb-3 mt-3 text-left">Rejected By Match</h4>
            </div>
            <div class="row">
                @if ($rejected_premium_meetings_list ?? '')
                    @foreach ($rejected_premium_meetings_list ?? '' as $Data)
                        <div class="col-3">
                            <div class="card product-box">
                                <div class="product-img">
                                    <div class="p-3 text-center">
                                        <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $Data->photo }}"
                                            alt="profile-pic" height="150px" width="150px">
                                    </div>
                                </div>
                                <div class="product-info border-top p-3">
                                    <div>
                                        <h5> Name : {{ $Data->name }}</h5>
                                        <h6> Marital Status : {{ $Data->marital_status }}</h6>
                                        <h6> Caste : {{ $Data->caste }}</h6>
                                        <h6> Profile Sent Date : {{ date('d/m/Y', strtotime($Data->created_at)) }}</h6>
                                        <h6> comment : </h6>
                                        <ol>
                                            @foreach (json_decode($Data->comments) as $item)
                                                <li>{{ $item->comment }}({{ $item->meeting_date }})</li>
                                            @endforeach
                                    </div>
                                </div> <!-- end product info-->
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <h4 class="text-primary mb-3 mt-3 text-left">Premium Meeting Result</h4>
            </div>
            <div class="row">
                @if ($premium_meetings_list ?? '')
                    @foreach ($premium_meetings_list ?? '' as $Data)
                        @if ($Data->status == '4')
                            <div class="col-3">
                                <div class="card product-box">
                                    <div class="product-img">
                                        <div class="p-3 text-center">
                                            <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $Data->photo }}"
                                                alt="profile-pic" height="150px" width="150px">
                                        </div>
                                    </div>
                                    <div class="product-info border-top p-3">
                                        <div>
                                            <h5> Name : {{ $Data->name }}</h5>
                                            <h6> Marital Status : {{ $Data->marital_status }}</h6>
                                            <h6> Caste : {{ $Data->caste }}</h6>
                                            <h6> Profile Sent Date : {{ date('d/m/Y', strtotime($Data->updated_at)) }}
                                            </h6>
                                            <h6> Status : Yes </h6>
                                            <h6> comment : </h6>
                                            <ol>
                                                @foreach (json_decode($Data->comments) as $item)
                                                    <li>{{ $item->comment }}({{ $item->meeting_date }})</li>
                                                @endforeach
                                        </div>
                                    </div> <!-- end product info-->
                                </div>
                            </div>
                        @endif
                        @if ($Data->status == '5')
                            <div class="col-3">
                                <div class="card product-box">
                                    <div class="product-img">
                                        <div class="p-3 text-center">
                                            <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/{{ $Data->photo }}"
                                                alt="profile-pic" height="150px" width="150px">
                                        </div>
                                    </div>
                                    <div class="product-info border-top p-3">
                                        <div>
                                            <h5> Name : {{ $Data->name }}</h5>
                                            <h6> Marital Status : {{ $Data->marital_status }}</h6>
                                            <h6> Caste : {{ $Data->caste }}</h6>
                                            <h6> Profile Sent Date : {{ date('d/m/Y', strtotime($Data->updated_at)) }}
                                            </h6>
                                            <h6> Status : No </h6>
                                            <h6> comment : </h6>
                                            <ol>
                                                @foreach (json_decode($Data->comments) as $item)
                                                    <li>{{ $item->comment }}({{ $item->meeting_date }})</li>
                                                @endforeach


                                            </ol>
                                        </div>
                                    </div> <!-- end product info-->
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <style>
        .complete-height {
            height: 99vh;
            margin: 10px 0px 10px 0px;
            page-break-after: always;
        }

        .watermark {
            background: url("{{ url('images/logo-sm-dark.png') }}") center center no-repeat;
            opacity: 0.1;
            opacity: 0.1;
            position: absolute;
            width: 100%;
            height: 100%;
        }

        @media print {

            html,
            body {
                border: 1px solid white;
                height: 99%;
                page-break-after: avoid;
                page-break-before: avoid;
            }
        }
    </style>
    <script src="{{ url('js/vendor.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            window.setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
</body>

</html>

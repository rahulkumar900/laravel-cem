@section('page-title', 'Pending Profiles')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css"
        integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Intercation Client List !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Client Interaction</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item">
                                <a href="#home-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Welcome Call</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                    <span class="d-none d-sm-inline-block">Verification Call</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home-b2">
                                <span class="welcomeDoneMsg"></span>
                                <table class="table table-striped table-inverse" id="callinteraction_table" width="100%">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Income</th>
                                            <th>Mobile</th>
                                            <th>Marital Status</th>
                                            <th>Profile Complete %</th>
                                            <th>Created At</th>
                                            <th>Last Seen</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="load_lead_data">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="profile-b2">
                                {{-- verification call --}}
                                <span class="verificationDoneMsg"></span>
                                <table class="table table-striped table-inverse" id="verificationcallinteraction_table"
                                    width="100%">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Income</th>
                                            <th>Mobile</th>
                                            <th>Marital Status</th>
                                            <th>Profile Complete %</th>
                                            <th>Created At</th>
                                            <th>Last Seen</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="load_lead_data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    @include('form.modelProgressForm', ['data' => 'test', 'new' => 'name', 'title' => 'Update Profile'])

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
        integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
    <script>
        $(document).ready(function() {

            // load approved profiles
            var table_data = $('#callinteraction_table').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getwelcomecallprofiles') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.genderCode_user == 1) {
                                return "Male";
                            } else {
                                return "Female";
                            }
                        }
                    },
                    {
                        data: 'monthly_income',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: 'marital_status',
                    },
                    {
                        data: 'profile_percent',
                    },
                    {
                        data: null,
                        render: function(data) {
                            data2 = data.created_at.split('T')
                            return data2[0]
                        }
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData =
                                `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.id}"
                                    user_id="${data.id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">View/Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.id}">Reject</button>
                                <button type="button" class="btn btn-sm btn-warning markWelcomeDone" userId="${data.id}"  userName="${data.name}">Mark Done</button>
                                 <button class="btn btn-success btn-xs viewDetails" leadid="${data.id}">View Details</button>
                                 <button class="btn btn-danger btn-xs mark_married" user_id="${data.id}">Mark Married</button>
                                 <button class="btn btn-purple btn-xs mark_premium" user_id="${data.id}">Mark Premium</button>
                                 <button class="btn btn-primary btn-xs addCreditButton" user_id="${data.id}">Add Credits</button>
                                 <input type="file" name="photo" accept="image/*" class="js-photo-upload"  user_id="${data.id}">`;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });

            $(document).on('click', '.markWelcomeDone', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('markwelcomedone') }}",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "user_id": $(this).attr('userId'),
                        "profile_name": $(this).attr('userName')
                    },
                    success: function(welcomeDoneResponse) {
                        var clrType;
                        var msgType;
                        var msgString;
                        if (welcomeDoneResponse.type == true) {
                            clrType = 'success';
                            msgType = 'Success';
                            msgString = `Marking Welcome Done For ${welcomeDoneResponse.name}`;
                        } else {
                            clrType = 'danger';
                            msgType = 'Warning';
                            msgString =
                                `Marking Welcome Failed For ${welcomeDoneResponse.name}`;
                        }
                        var htmlData = `<div class="alert alert-${clrType} alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>${msgType}</strong> ${msgString}
                                    </div>`;
                        $('.welcomeDoneMsg').html(htmlData);
                        table_data.ajax.reload();
                        window.setTimeout(function() {
                            $('.welcomeDoneMsg').html('');
                        }, 2000);
                    }
                })
            });


            var verificationtable_data = $('#verificationcallinteraction_table').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getverificationcallprofiles') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.genderCode_user == 1) {
                                return "Male";
                            } else {
                                return "Female";
                            }
                        }
                    },
                    {
                        data: 'monthly_income',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: 'marital_status',
                    },
                    {
                        data: 'profile_percent',
                    },
                    {
                        data: function(data) {
                            if (data.created_at != null) {
                                return data.created_at.split('T')[0];
                            } else {
                                return null;
                            }
                        },
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData =
                                `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.id}"
                                    user_id="${data.id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">View/Edit</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.id}">Reject</button>
                                <button type="button" class="btn btn-sm btn-warning markVerificationDone" userId="${data.id}"  userName="${data.name}">Mark Done</button>
                                 <button class="btn btn-success btn-xs viewDetails" leadid="${data.id}">View Details</button>
                                 <button class="btn btn-danger btn-xs mark_married" user_id="${data.id}">Mark Married</button>
                                 <button class="btn btn-purple btn-xs mark_premium" user_id="${data.id}">Mark Premium</button>
                                 <button class="btn btn-primary btn-xs addCreditButton" user_id="${data.id}">Add Credits</button>
                                 <input type="file" name="photo" accept="image/*" class="js-photo-upload"  user_id="${data.id}">`;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });

            $(document).on('click', '.markVerificationDone', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('markverificationdone') }}",
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "user_id": $(this).attr('userId'),
                        "profile_name": $(this).attr('userName')
                    },
                    success: function(welcomeDoneResponse) {
                        var clrType;
                        var msgType;
                        var msgString;
                        if (welcomeDoneResponse.type == true) {
                            clrType = 'success';
                            msgType = 'Success';
                            msgString = `Marking Welcome Done For ${welcomeDoneResponse.name}`;
                        } else {
                            clrType = 'danger';
                            msgType = 'Warning';
                            msgString =
                                `Marking Welcome Failed For ${welcomeDoneResponse.name}`;
                        }
                        var htmlVerData = `<div class="alert alert-${clrType} alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>${msgType}</strong> ${msgString}
                                    </div>`;
                        $('.verificationDoneMsg').html(htmlVerData);
                        verificationtable_data.ajax.reload();
                        window.setTimeout(function() {
                            $('.verificationDoneMsg').html('');
                        }, 2000);
                    }
                })
            });



            $(document).on('click', '.rejectProfile', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Reject")) {
                    $.ajax({
                        url: "{{ route('rejectuserprofile') }}",
                        type: "get",
                        data: {
                            "user_id": $(this).attr('userid')
                        },
                        success: function(rejectResponse) {
                            alert(rejectResponse.message);
                            table_data.ajax.reload();
                        }
                    });
                }
            });



            $(document).on('submit', '#addLeadForm', function(e) {
                e.preventDefault();
                $('.btn_div').text(`Please Wait...............`);
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(submitResponse) {
                        var messageHtml = ``;
                        if (submitResponse.type == true) {
                            messageHtml +=
                                `<div class="alert alert-success" role="alert"><strong>Message !</strong> ${submitResponse.message}</div>`;
                            $('#addLeadForm')[0].reset();
                            window.setTimeout(function() {
                                $('.form_output').html('');
                                table_data.ajax.reload();
                                $('#approveProfile').modal('hide');
                            }, 2000);
                        } else {
                            messageHtml +=
                                `<div class="alert alert-danger" role="alert"><strong>Message !</strong> ${submitResponse.message}</div>`;
                        }
                        $('.btn_div').html(
                            `<button type="button" class="btn btn-danger rejectProfile rejectButton">Reject</button><button type="submit" name="submit" class="btn btn-success">Update</button>`
                        );
                        $('.form_output').html(messageHtml);
                    }
                });
            });


            // load all users
            loadAllTemples();

            function loadAllTemples() {
                var temple_html = '<option value="">Select User</option>';
                var login_user = "{{ Auth::user()->temple_id }}";
                var temple_id_html = '';
                $.ajax({
                    url: "{{ route('getalltemples') }}",
                    type: "get",
                    success: function(temple_response) {
                        for (let l = 0; l < temple_response.length; l++) {
                            const temple_list = temple_response[l];
                            if (temple_list.temple_id == login_user) {
                                temple_html +=
                                    `<option selected="selected" value="${temple_list.temple_id}">${temple_list.name}</option>`;
                                temple_id_html +=
                                    `<option selected="selected" value="${temple_list.id}">${temple_list.name}</option>`;
                            } else {
                                temple_html +=
                                    `<option value="${temple_list.temple_id}">${temple_list.name}</option>`;
                                temple_id_html +=
                                    `<option value="${temple_list.id}">${temple_list.name}</option>`;
                            }
                        }
                        $('#assign_to').html(temple_html);
                        $("#appoinemtn_with").html(temple_id_html);
                    }
                });
            }

            // get qualification by id
            function getQualificationById(qualification) {
                $.ajax({
                    url: "{{ route('getqualificationById') }}",
                    type: "get",
                    data: {
                        "qualification": qualification
                    },
                    success: function(qualresponse) {
                        localStorage.setItem('userDegree', qualresponse.degree_name);
                    }
                });
            }
        });
    </script>
@endsection

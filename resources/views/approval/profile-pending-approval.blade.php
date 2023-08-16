@section('page-title', 'Pending Profiles')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css"
        integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Profile Pending !</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                                <li class="breadcrumb-item active">Approve Pending Profiles</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <div class="col-12 d-flex justify-content-between">

                                <h4 class="header-title">Pending Profiles <span id="spinner_loading"
                                        style="display: none">Loading
                                        Wait <div class="spinner-grow text-success"></div></span></h4>

                                <h4 class="header-title">Today Aprove Profile:<span id="approve_by_me">0</span>
                                </h4>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <table class="table table-striped table-inverse" id="approval_tble">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Assign To</th>
                                            <th>Gender</th>
                                            <th>Income</th>
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
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
        </div>
        @include('form.modelProgressForm', [
            'data' => 'test',
            'new' => 'name',
            'title' => 'Approve Profile',
        ])



        {{-- add photos modal ends --}}


        <style>
            #slider-div {
                display: flex;
                flex-direction: row;
                margin-top: 30px;
            }

            #slider-div>div {
                margin: 8px;
            }

            .slider-label {
                position: absolute;
                background-color: #eee;
                padding: 4px;
                font-size: 0.75rem;
            }

            .cropper-container {
                margin: 0 auto 20px auto;
            }
        </style>

    @endsection
    @section('custom-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
            integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script>
            var cat_type = "profile"
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
            $(document).on('click', '.markMarried', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Mark Married")) {
                    $.ajax({
                        url: "{{ route('markMarrieduserprofile') }}",
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

            $(document).ready(function() {
                toatalWork()
                // load approved profiles
                var table_data = $('#approval_tble').DataTable({
                    "order": [
                        [1, "asc"]
                    ],
                    "processing": true,
                    "ajax": {
                        "url": "{{ route('pendingprofiledata') }}",
                        "type": "get",
                    },
                    "columns": [{
                            data: 'name',
                        },
                        {
                            data: 'mobile',
                        },
                        {
                            data: 'assign_to',
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
                            data: null,
                            render: function(data) {
                                // toatalWork()
                                if (data.annual_income > 10) {
                                    return (parseFloat(data.annual_income) / 100000).toFixed(2) + "LPA";
                                } else {
                                    return data.annual_income + "LPA";
                                }
                            }
                        },

                        {
                            data: 'marital_status',
                        },
                        {
                            data: 'profile_percent',
                        },
                        {
                            data: 'created_at',
                        },
                        {
                            data: 'last_seen',
                        },
                        {
                            data: null,
                            render: function(data) {
                                var btnData = `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.user_id}"
                                    user_id="${data.user_id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">Check & Approve</span>
                                </button>
                                <p> https://hansmatrimony.com/fourReg/fullPage/edit/${data.user_id}/0</p>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.user_id}">Reject</button>
                                <button type="button" class="btn btn-sm btn-danger markMarried" id="${data.user_id}" userId="${data.user_id}">Mark Married</button>
                                  <input type="file" name="photo" accept="image/*" class="js-photo-upload" userId="${data.user_id}">
                                `;
                                return btnData;
                            },
                            bsortable: false,
                        }
                    ]
                });


                $(document).on('submit', '#addLeadForm', function(e) {
                    e.preventDefault();
                    $('.btn_div').text(`Please Wait...............`);
                    $('.loader').show();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(submitResponse) {
                            $('.loader').hide();
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
                                `<button type="submit" name="submit" class="btn btn-success">Approve</button>`
                            );
                            $('.form_output').html(messageHtml);
                        }
                    });
                });

            });
        </script>
        @include('form.script.modelProgressFormScript')
    @endsection

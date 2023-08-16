@section('page-title', 'Approved Profiles')
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
                    <h4 class="page-title">Approved Profiles !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Approved Profiles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse" id="approval_tble">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Income</th>
                                        <th>Mobile</th>
                                        <th>Marital Status</th>
                                        <th>Profile Complete %</th>
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
    @include('form.modelProgressForm', ['data' => 'test', 'new' => 'name', 'title' => 'Update Profile'])

    {{-- add credit modal starts --}}

    <div class="modal fade" id="addCreditModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Credits</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addusercredit') }}" method="post" id="addCreditForm">
                        @csrf
                        <div class="form-group">
                            <input type="number" class="form-control d-none" placeholder="Add Credit" name="user_id"
                                id="credit_user_id">
                            <input type="number" min="1" class="form-control" placeholder="Add Credit"
                                name="credit_total" required>
                        </div>
                        <div class="form-group formMessage mb-3">

                        </div>
                        <div class="form-group float-end">
                            <button type="submit" name="submit" class="btn btn-sm btn-danger">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- add credit modal ends --}}

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
    <script>
        $(document).ready(function() {


            // upload image to server ends

            $('#search_lead_modal').modal('show');

            /************* data loading and sending whatsapp message ends *************/

            // select gender automatically


            // load profiles for approval
            var table_data = $('#approval_tble').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getapprovedprofiles') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.gender == 1) {
                                return "Male";
                            } else if (data.gender == 2) {
                                return "Female";
                            } else {
                                return data.gender;
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.annual_income > 10) {
                                return (parseFloat(data.annual_income) / 100000).toFixed(2) + "LPA";
                            } else {
                                return data.annual_income + "LPA";
                            }
                        }
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
                        data: 'last_seen',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData = `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    user_id="${data.user_id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">Update</span>
                                </button>
                                <button
                                    data-toggle="tooltip" data-placement="top" title="Add Credit"
                                    user_id="${data.user_id}"
                                    class="btn btn-purple btn-sm addCreditButton">
                                    <span style="color: white;">Add Credit</span>
                                </button>
                                <p> https://hansmatrimony.com/fourReg/fullPage/edit/${data.lead_id}/1</p>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.user_id}">Reject</button>
                                  <input type="file" name="photo" accept="image/*" class="js-photo-upload" userId="${data.user_id}">
                                `;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });

            // add credit
            $(document).on('click', '.addCreditButton', function(e) {
                e.preventDefault();
                let userID = $(this).attr('user_id');
                $('#credit_user_id').val(userID);
                $('#addCreditModel').modal('show');
            });

            $(document).on('submit', '#addCreditForm', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(creditResp) {
                        if (creditResp.type == true) {
                            $('.formMessage').html(
                                `<div class="alert alert-success" role="alert"><strong>Success </strong>${creditResp.message}</div>`
                            );
                            $('#addCreditForm')[0].reset();
                            window.setTimeout(function() {
                                $('.formMessage').html('');
                                $('#addCreditModel').modal('hide');
                            }, 2000);
                        }
                    }
                })
            });


            /*
             reject profile
            */
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
                            `<button type="submit" name="submit" class="btn btn-success">Approve</button>`
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

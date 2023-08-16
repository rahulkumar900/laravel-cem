@section('page-title', 'Login Other Account')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-left">
                        <h4 class="page-title">Welcome !</h4>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Others</a></li>
                            <li class="breadcrumb-item active">Login Other Account</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" style="overflow: scroll">
                        <div class="row">
                            <div class="col-md-10">
                                <h4 class="header-title">User List</h4>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-xs btn-success float-right opne_model">Add</button>
                            </div>
                        </div>

                        <table id="userlist-table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>role</th>
                                    <th>Login Time</th>
                                    <th>Off Day</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- user details model --}}
    <div class="modal fade" id="edit_user_model" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('saveuserdetails') }}" method="post" id="user_form">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="form-group mb-3 row">
                            <div class="col-md-6">
                                <input type="text" name="user_name" id="user_name" placeholder="user name"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="user_email" id="user_email" placeholder="user email"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <div class="col-md-6">
                                <select name="select_role" id="select_role" class="form-select">
                                    <option value="">Select Role</option>
                                    <option value="0">Matchmaker</option>
                                    <option value="1">Customer Support</option>
                                    <option value="2">Approvals</option>
                                    <option value="3">RM</option>
                                    <option value="5">Tele Sales</option>
                                    <option value="7">Team Leader</option>
                                    <option value="11">Work from home</option>
                                    <option value="12">Marketting</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <select name="off_day" id="off_day" class="form-select">
                                    <option value="">Select Day Off</option>
                                    <option value="mon">Monday</option>
                                    <option value="tue">Tuesday</option>
                                    <option value="wed">Wednesday</option>
                                    <option value="thu">Thursday</option>
                                    <option value="fri">Friday</option>
                                    <option value="sat">Saturday</option>
                                    <option value="sun">Sunday</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <div class="col-md-6">
                                <label for="">Login Start</label>
                                <input type="time" name="login_start" id="login_start" placeholder="user name"
                                    class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Login End</label>
                                <input type="time" name="login_end" id="login_end" placeholder="user email"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="form-group mb-3 row">
                            <div class="col-md-12">
                                <label for="">Mobile Number</label>
                                <input type="nummber" name="user_mobile" id="user_mobile" placeholder="Mobile number"
                                    class="form-control" maxlength="10" minlength="10">
                            </div>
                        </div>

                        {{-- <div class="form-group mb-3 row">
                        <div class="col-md-6">
                            <input type="number" name="working_hours" id="working_hours" placeholder="Working Hours" class="form-control" min="1" max="24">
                        </div>
                        <div class="col-md-6">
                            <input type="number" name="max_late_limit" id="max_late_limit" placeholder="Max Late Limit" class="form-control">
                        </div>
                    </div> --}}

                        <div class="form-group mb-3 row">
                            <div class="col-md-9 show_output"></div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success float-end"><i class="fa fa-user-plus"
                                        aria-hidden="true"></i> Save</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- user -details model ends --}}
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // get data from server
            table_data = $('#userlist-table').DataTable({
                "order": [
                    [6, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('getallusers') }}",
                "columns": [{
                        data: 'name'
                    },
                    {
                        data: 'mobile'
                    },
                    {
                        data: null,
                        render: function(data) {
                            let roleString = "";
                            if (data.role == 1) {
                                roleString = "customer care";
                            } else if (data.role == 2) {
                                roleString = "approval";
                            } else if (data.role == 3) {
                                roleString = "matchmaker";
                            } else if (data.role == 9) {
                                roleString = "Team Leader";
                            } else if (data.role == 5) {
                                roleString = "Tele Sales";
                            } else {
                                roleString = "N.A.";
                            }
                            return roleString;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.morning + '-' + data.evening;
                        }
                    },
                    {
                        data: 'dayoff'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<button class="btn btn-xs waves-effect waves-light btn-success btn_login" id="${data.id}" data-toggle="tooltip" data-placement="top" title="Sign In"><i class="fas fa-sign-in-alt"></i></button>

                            <button class="btn btn-xs btn-warning waves-effect waves-light btn_edit_user" id="${data.id}" data-toggle="tooltip" data-placement="top" title="Edit User Data" user_name="${data.name}" user_email="${data.email}" off_day="${data.dayoff}" user_role="${data.role}" user_mobile="${data.mobile}" morning="${data.morning}" evening="${data.evening}"><i class="fas fa-pencil-alt " aria-hidden="true"></i></button>

                            <button class="btn btn-xs btn-danger waves-effect waves-light btn_deactivate" id="${data.id}" data-toggle="tooltip" data-placement="top" title="Remove User"><i class="fa fa-user-times" aria-hidden="true"></i></button>`;
                        }
                        //"bSortable": false,
                    },
                ]
            });

            $(document).on('submit', '#user_form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(dataSave) {
                        if (dataSave.type == true) {
                            $('.show_output').html(
                                '<div class="alert alert-success alert-dismissible fade show" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> <span class="sr-only">Close</span> </button> <strong>Holy guacamole!</strong> ' +
                                dataSave.message + '. </div>');
                            $('#user_form')[0].reset();
                            table_data.ajax.reload();
                        }
                    }
                })
            });

            // login other account
            $(document).on('click', '.btn_login', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "get",
                    url: "{{ route('loginotheraccount') }}",
                    data: {
                        "user_id": $(this).attr('id')
                    },
                    success: function(log_resp) {
                        if (log_resp.type == true) {
                            alert(log_resp.message);
                            window.location.href = "{{ route('userdashboard') }}";
                        } else {

                        }
                    }
                });
            });

            $(document).on('click', '.btn_edit_user', function(e) {
                e.preventDefault();
                $('#user_id').val($(this).attr('id'));
                $('#user_name').val($(this).attr('user_name'));
                $('#user_email').val($(this).attr('user_email'));
                if ($(this).attr('off_day').length == 3) {
                    $('#off_day').val($(this).attr('off_day'));
                } else {
                    $('#off_day').val('mon');
                }
                $('#select_role').val($(this).attr('user_role'));
                $('#login_start').val($(this).attr('morning'));
                $('#login_end').val($(this).attr('evening'));
                $('#user_mobile').val($(this).attr('user_mobile'));
                $('#edit_user_model').modal('show');
            });

            $(document).on('click', '.opne_model', function(e) {
                e.preventDefault();
                $('#edit_user_model').modal('show');
            });
        });
    </script>
@endsection

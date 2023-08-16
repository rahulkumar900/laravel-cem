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
                            <li class="breadcrumb-item active">User Login Details</li>
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
                            <div class="col-md-6">
                                <h4 class="header-title">User List</h4>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="login_date"
                                    id="login_date" placeholder="Mobile Number" required="">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success float-right search_record"><i class="fa fa-search"
                                        aria-hidden="true"></i></button>
                            </div>
                        </div>

                        <table id="userlist-table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Temple</th>
                                    <th>Mobile</th>
                                    <th>Login Time</th>
                                    <th>Logout Time</th>
                                    <th>System IP</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="login_response_display">
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            $(document).ready(function() {
                $(document).on('click', '.search_record', function(e) {
                    e.preventDefault();
                    $('.search_record').text("Please Wait");
                    var loginDate = $('#login_date').val();
                    $.ajax({
                        type: "get",
                        url: "{{route('getuserlogindetails')}}",
                        data: {
                            "login_date": loginDate
                        },
                        success: function(searchResponse) {
                            var tableHtml = '';
                            for (let i = 0; i < searchResponse.length; i++) {
                                const loginData = searchResponse[i];
                                tableHtml += ` <tr>
                                                <td scope="row">${i+1}</td>
                                                <td>${loginData.user_relation.name}</td>
                                                <td>${loginData.user_relation.mobile}</td>
                                                <td>${loginData.login_time}</td>
                                                <td>${loginData.logout_time ?? "NA"}</td>
                                                <td>${loginData.ip_address}</td>
                                                <td>`;
                                if (loginData.login_status == 0) {
                                    tableHtml +=
                                        '<span class="text-danger">Failed</span>';
                                } else {
                                    tableHtml +=
                                        '<span class="text-success">Success</span>';
                                }
                                tableHtml += `</td>
                                                <td>`;
                                if (loginData.logout_time) {
                                    tableHtml += ``;
                                } else {
                                    tableHtml +=
                                        `<button class="btn btn-danger btn-sm btnLogout" recordId="${loginData.id}">Logout</button>`;
                                }
                                tableHtml += `</td>
                                            </tr>`;
                            }
                            $('.login_response_display').html(tableHtml);
                            $('.search_record').html('<i class="fa fa-search"aria-hidden="true">');
                        }
                    });
                });

                $(document).on('click', '.btnLogout', function(e) {
                    e.preventDefault();
                    if (confirm("Are You Sure To Logout")) {
                        $.ajax({
                            url: "{{route('updatelogouttime')}}",
                            type: "get",
                            data: {
                                "record_id": $(this).attr('recordId')
                            },
                            success: function(dataResponseLogout) {
                                if (dataResponseLogout.type == true) {
                                    $('.search_record').trigger('click');
                                    alert("User Logout Successfully");
                                } else {
                                    alert("failed to Logout");
                                }
                            }
                        });
                    }

                });

            });

        });
    </script>
@endsection

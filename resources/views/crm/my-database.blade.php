@section('page-title', 'Double Approval')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Profile Details</h4>
                    <div class="page-title-left">
                        <a href="#"
                            class="btn btn-sm btn-bordered btn-purple search_lead waves-effect waves-light float-right">Overall
                            Search</a>
                    </div>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">My Database</a></li>
                            <li class="breadcrumb-item active">Database Profiles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="col-md-12 showPhotos">
                            <table class="table table-striped table-inverse" id="user-list-table">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Residance</th>
                                        <th>Manglik</th>
                                        <th>Reg Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    @include('form.modelProgressForm', ['data' => 'test', 'new' => 'name', 'title' => 'Edit Profile'])

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
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
        $(document).ready(function() {
            var databaseTable = $('#user-list-table').DataTable({
                "order": [
                    [4, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('myuserlist') }}",
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: 'gender',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `${data.working_city}`;
                        }
                    },
                    {
                        data: 'manglik',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var returnHtml = '<div class="d-flex">';
                            returnHtml =
                                `<button class="btn btn-success btn-xs viewDetails" leadid="${data.user_id}">View Details</button>
                                 <button class="btn btn-warning btn-xs checkNUpdate" user_id="${data.user_id}">Edit Details</button>
                                 <button class="btn btn-danger btn-xs mark_married" user_id="${data.user_id}">Mark Married</button>
                                 <button class="btn btn-purple btn-xs mark_premium" user_id="${data.user_id}">Mark Premium</button>
                                 <button class="btn btn-primary btn-xs addCreditButton" user_id="${data.user_id}">Add Credits</button>
                                 <input type="file" name="photo" accept="image/*" class="js-photo-upload" userId="${data.user_id}">
                                 `;

                            returnHtml += '</div>';
                            return returnHtml;
                        }
                    }
                ]
            });


            $(document).on('click', '.link_to_profess', function() {
                $('#profile-tab-1').removeClass('active');
                $('.personal_nav_link').removeClass('active');
                $('#profile-tab-2').addClass('active');
                $('.profile_tabl_2').addClass('active');
            });

            /*
             reject profile
            */
            $(document).on('click', '.rejectProfile', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Reject")) {
                    $('.loader').show();
                    $.ajax({
                        url: "{{ route('rejectuserprofile') }}",
                        type: "get",
                        data: {
                            "user_id": $(this).attr('userid')
                        },
                        success: function(rejectResponse) {
                            $('.loader').hide();
                            alert(rejectResponse.message);
                            table_data.ajax.reload();
                        }
                    });
                }
            });

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

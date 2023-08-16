@section('page-title', 'Designation Management')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Welcome !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Designation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-6 offset-lg-3">

                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Designation Management Form</h4>
                        <p class="sub-header">
                            Here You Can Add / Update Designation Datas.
                        </p>
                        <form action="{{ route('savedesignationmaster') }}" class="parsley-examples" id="department_form"
                            method="post" autocomplete="off">
                            @csrf
                            <div class="mb-2">
                                <label for="userName" class="form-label">Department Name<span
                                        class="text-danger">*</span></label>
                                <select name="department_name" required class="form-control text-capitalize"
                                    id="department_name">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="userName" class="form-label">Designation Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="designation_name[]" parsley-trigger="change" required
                                    placeholder="Enter designation name" class="form-control" id="designation_name">
                                <input type="number" name="designation_id" parsley-trigger="change"
                                    placeholder="Enter email" class="form-control d-none" id="designation_id">
                            </div>
                            <div class="mb-2 row_append row">
                            </div>
                            <div class="mb-2 display_outputs">
                                <button type="button"
                                    class="btn btn-sm btn-success waves-effect waves-light me-1 btn_add_row"><i
                                        class="ri-add-box-fill"></i></button>
                            </div>
                            <div class="text-end btn_div">
                                <span id="sl">1</span>
                                <button class="btn btn-primary waves-effect waves-light me-1" type="submit">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div> <!-- end card -->
            </div>
            <!-- end col -->
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Designation List</h4>

                        <table id="department-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Name</th>
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
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            $('.btn_div').html(
                ' <button class="btn btn-primary waves-effect waves-light me-1" type="submit">Submit</button>');
            $('#department_id').val('');
            $('#designation_id').val('');
            $('.display_outputs').html(
                '<button type="button" class="btn btn-sm btn-success waves-effect waves-light me-1 btn_add_row"><i class="ri-add-box-fill"></i></button><button class="btn btn-warning waves-effect waves-light me-1 btn-sm resetButton" type="button"">Reset</button>'
            );

            $(document).on('click','.resetButton',function(){
                if(confirm("Are You Sure To Reset Form?")){
                    window.location.reload();
                }
            });

            $(document).on('submit', '#department_form', function(e) {
                e.preventDefault();
                var message_html = '';
                $('.btn_div').html(
                    '<div class="spinner-border text-primary m-2" role="status"><span class="visually-hidden"></span></div>'
                );
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(form_response) {
                        if (form_response.type == true) {
                            $('.btn_div').html(
                                '<button class="btn btn-primary waves-effect waves-light me-1" type="submit">Submit</button>'
                            );
                            message_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>Alert!</strong> ${form_response.message}
                                        </div>`;
                            $('#department_form')[0].reset();
                            $('.row_append').html('');
                            window.setTimeout(function() {
                                $('.display_outputs').html(
                                    '<button type="button" class="btn btn-sm btn-success waves-effect waves-light me-1 btn_add_row"><i class="ri-add-box-fill"></i></button>'
                                );
                                $('#department-datatable').DataTable().ajax.reload();
                                $('.btn_div').html(
                                    ' <button class="btn btn-primary waves-effect waves-light me-1" type="submit">Submit</button>'
                                );
                                $('#department_name').val('');
                                $('#designation_id').val('');
                            }, 2000);
                        } else {
                            $('.btn_div').html(
                                '<button class="btn btn-warning waves-effect waves-light me-1" type="submit">Submit</button>'
                            );
                            $.each(form_response.message, function(key, value) {
                                message_html += `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <strong>Alert!</strong> ${value}
                                        </div>`;
                            });
                        }
                        $('.display_outputs').html(message_html);
                    }
                });
            });

            // get department json
            getDepartmentJson();

            function getDepartmentJson() {
                var dr_html = '';
                $.ajax({
                    url: "{{ route('departmentjson') }}",
                    'type': 'get',
                    success: function(dept_resp) {
                        dr_html += '<option value="">Select Department</option>';
                        for (let i = 0; i < dept_resp.data.length; i++) {
                            const dept_data = dept_resp.data[i];
                            dr_html +=
                                `<option value="${dept_data.id}">${dept_data.department_name}</option>`;
                        }
                        $('#department_name').html(dr_html);
                    }
                })
            }

            // get data from server
            $('#department-datatable').DataTable({
                "order": [
                    [2, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('getalldesignations') }}",
                "columns": [{
                        data: null,
                        render: function(data) {
                            return `<span class="text-capitalize">${data.designation}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<span class="text-capitalize">${data.department.department_name}</span>`;
                        }
                    },
                    {
                        data: null,
                        render : function(data){
                            let newDate =data.created_at.split('T');
                            return newDate[0];
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<button class="btn btn-warning btn-rounded waves-effect waves-light btn_edit" id="${data.id}" desig_name="${data.designation}" dept_id="${data.department.id}">Edit</button>
                            <button class="btn btn-danger btn-rounded waves-effect waves-light btn_delete" id="${data.id}">Delete</button>`;
                        }
                    },
                ]
            });

            // edit
            $(document).on('click', '.btn_edit', function(e) {
                e.preventDefault();
                $('.btn_div').html(
                    ' <button class="btn btn-warning waves-effect waves-light me-1" type="submit">Update</button>'
                );
                $('.display_outputs').html('');
                $('#designation_name').val($(this).attr('desig_name'));
                $('#department_name').val($(this).attr('dept_id'));
                $('#designation_id').val($(this).attr('id'));
            });

            // delete
            $(document).on('click', '.btn_delete', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Delete?")) {
                    $.ajax({
                        url: "{{ route('deletedepartment') }}",
                        type: 'get',
                        data: {
                            'dept_id': $(this).attr('id')
                        },
                        success: function(del_resp) {
                            if (del_resp.type == true) {
                                alert(del_resp.message);
                                window.setTimeout(function() {
                                    $('#department-datatable').DataTable().ajax
                                        .reload();
                                }, 500);
                            } else {
                                alert(del_resp.message);
                            }
                        }
                    });
                }
            });

            // add more rows
            $(document).on('click', '.btn_add_row', function(e) {
                e.preventDefault();
                var sl_nos = $('#sl').text();
                new_sl = parseInt(sl_nos) + 1;
                $('#sl').text(new_sl);
                var new_input = '';
                new_input =
                    `<div class="col-md-10 mb-2" id="row_nos${new_sl}"><input type="text" name="designation_name[]" parsley-trigger="change"  placeholder="Enter designation name" class="form-control" id="designation_name">
            <input type="number" name="department_id${new_sl}" parsley-trigger="change" placeholder="Enter email" class="form-control d-none" id="department_id"></div><div class="col-md-2 mb-2" id="btn_div${new_sl}"><button type="button" class="btn btn-sm btn-danger waves-effect waves-light me-1 btn_del_row" id="${new_sl}">X</button></div>`;
                $('.row_append').append(new_input);
            });

            $(document).on('click', '.btn_del_row', function(e) {
                e.preventDefault();
                $('#row_nos' + $(this).attr('id')).remove();
                $('#btn_div' + $(this).attr('id')).remove();
            });
        });
    </script>
@endsection

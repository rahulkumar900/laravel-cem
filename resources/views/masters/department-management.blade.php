@section('page-title','Department Management')
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
                        <li class="breadcrumb-item active">Department</li>
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
                    <h4 class="header-title">Department Management Form</h4>
                    <p class="sub-header">
                        Here You Can Add / Update Department Datas.
                    </p>

                    <form action="{{route('savedepartmentmaster')}}" class="parsley-examples" id="department_form" method="post">
                        @csrf
                        <div class="mb-2">
                            <label for="userName" class="form-label">Department Name<span class="text-danger">*</span></label>
                            <input type="text" name="department_name" parsley-trigger="change" required
                                   placeholder="Enter department name" class="form-control" id="department_name">
                        </div>
                        <div class="mb-2">
                            <input type="number" name="department_id" parsley-trigger="change"
                                   placeholder="Enter email" class="form-control d-none" id="department_id">
                        </div>
                        <div class="mb-2 display_outputs">
                        </div>
                        <div class="text-end btn_div">
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
                    <h4 class="header-title">Department List</h4>

                    <table id="department-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl</th>
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
    $(document).ready(function(){
        $('.btn_div').html(' <button class="btn btn-primary waves-effect waves-light me-1" type="submit">Submit</button>');
            $('#department_name').val('');
            $('#department_id').val('');
        $(document).on('submit','#department_form',function(e){
            e.preventDefault();
            $('.btn_div').html('<div class="spinner-border text-primary m-2" role="status"><span class="visually-hidden"></span></div>');
            $.ajax({
                url : $(this).attr('action'),type: $(this).attr('method'),data:$(this).serialize(),success : function(form_response){
                    if(form_response.type==true){
                        $('.btn_div').html('<button class="btn btn-primary waves-effect waves-light me-1" type="submit">Submit</button>');
                        message_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>Alert!</strong> ${form_response.message}
                                        </div>`;
                        $('#department_form')[0].reset();
                    }else{
                        $('.btn_div').html('<button class="btn btn-warning waves-effect waves-light me-1" type="submit">Submit</button>');
                        message_html = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <strong>Alert!</strong> ${form_response.message}
                                        </div>`;
                    }
                    $('.display_outputs').html(message_html);
                    window.setTimeout(function(){$('.display_outputs').html('');$('#department-datatable').DataTable().ajax.reload(); $('.btn_div').html(' <button class="btn btn-primary waves-effect waves-light me-1" type="submit">Submit</button>');$('#department_name').val('');$('#department_id').val('');},2000);
                }
            });
        });

        // get data from server
        $('#department-datatable').DataTable({
            "order": [[ 2, "desc" ]],
             "processing": true,
                "ajax": "{{route('getalldepartments')}}",
                "columns": [
                    {data: 'sl'},
                    {data: 'department_name'},
                    {data: 'created_at'},
                    {data: 'button',"bSortable": false,},
                ]
        });

        // edit
        $(document).on('click','.btn_edit',function(e){
            e.preventDefault();
            $('.btn_div').html(' <button class="btn btn-warning waves-effect waves-light me-1" type="submit">Update</button>');
            $('#department_name').val($(this).attr('dept_name'));
            $('#department_id').val($(this).attr('id'));
        });

        // delete
        $(document).on('click','.btn_delete',function(e){
            e.preventDefault();
            if(confirm("Are You Sure To Delete?")){
                $.ajax({url : "{{route('deletedepartment')}}",type:'get',data:{'dept_id':$(this).attr('id')},success : function(del_resp){if(del_resp.type==true){alert(del_resp.message);window.setTimeout(function(){$('#department-datatable').DataTable().ajax.reload();},500);}else{alert(del_resp.message);}}});
            }
        });

    });
</script>
@endsection


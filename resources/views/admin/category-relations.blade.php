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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Category</a></li>
                            <li class="breadcrumb-item active">Manage</li>
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
                                    <span class="d-none d-sm-inline-block">Relation Category</span> (<span
                                        class="count_rel_cats"></span>)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                    <span class="d-none d-sm-inline-block">Relation Category Assign</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#messages-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-email-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Facebook Query Management</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                    <span class="d-none d-sm-inline-block">Website Leads Management</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home-b2">
                                <div class="float-end">
                                    <button class="btn btn-pink waves-effect waves-light btn-xs add_new_rel_cat">Add
                                        New</button>
                                </div>
                                <table class="table table-bordered table-striped rel_cat_table">
                                </table>
                            </div>
                            <div class="tab-pane" id="profile-b2">
                                <div class="float-end">
                                    <button class="btn btn-pink waves-effect waves-light btn-xs assign_new_rel_cat">Assign
                                        New Category</button>
                                </div>
                                <table class="table" id="relation_category_table" style="width: 100%">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Relation Category Name</th>
                                            <th>Temple Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="messages-b2">
                                <table class="table table-striped table-inverse">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Relation Ctegory Name</th>
                                            <th>Call Count</th>
                                            <th>No of Days Back</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="facebook_query_data">
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="settings-b2">
                                 <table class="table table-striped table-inverse">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Relation Ctegory Name</th>
                                            <th>Call Count</th>
                                            <th>No of Days Back</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="website_query_data">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>


    <!-- Relation Category Modal -->
    <div class="modal fade" id="relation_category_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Category Relation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('savetrelationcats') }}" method="post" id="relation_cat_form">
                        @csrf
                        <input type="hidden" name="level_id" id="level_id" value="0">
                        <div class="form-group mb-3">
                            <input type="text" name="caregory_name" id="caregory_name" class="form-control"
                                placeholder="Threshold Value">
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" id="threshold_value" name="threshold_value" class="form-control"
                                placeholder="Threshold Value">
                        </div>
                        <div class="form-group mb-3 form_otput_category_relat">

                        </div>
                        <div class="form-group mb-3 float-end">
                            <button class="btn btn-success waves-effect waves-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Assign New Relation Category Modal -->
    <div class="modal fade" id="assign_relation_category_modal" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Relation</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('saveassigntrelationcats') }}" method="post" id="assign_relation_cat_form">
                        @csrf
                        <input type="hidden" name="assign_relation_id" id="assign_relation_id" value="0">
                        <div class="form-group mb-3">
                            <select name="relation_select" id="relation_select" class="form-select">
                                <option value="">Select Relation</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <select name="temple_select" id="temple_select" class="form-select">
                                <option value="">Select Relation</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 form_otput_ass_category_relat">

                        </div>
                        <div class="form-group mb-3 float-end">
                            <button class="btn btn-success waves-effect waves-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit FB Query Modal -->
    <div class="modal fade" id="eqit_query_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Facebook Query</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('editfacebookquery')}}" method="post" id="edit_query_form">
                        @csrf
                         <input type="hidden" name="fb_query_id" id="fb_query_id">
                        <div class="form-group mb-3">
                            <input type="text" id="category_name_query" class="form-control" name="category_name_query" placeholder="Relation Category Name">
                        </div>
                        <div class="form-group mb-3">
                             <input type="text" id="call_count_query" class="form-control" name="call_count_query" placeholder="Relation Category Name">
                        </div>
                        <div class="form-group mb-3">
                             <input type="text" id="days_back_query" class="form-control" name="days_back_query" placeholder="Relation Category Name">
                        </div>
                        <div class="form-group mb-3 form_otput_edit_category_relat">

                        </div>
                        <div class="form-group mb-3 float-end">
                            <button type="submit" class="btn btn-success waves-effect waves-light">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit web Query Modal -->
    <div class="modal fade" id="eqit_web_query_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Website Query</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('editwebquery')}}" method="post" id="edit_web_query_form">
                        @csrf
                        <input type="hidden" name="web_query_id" id="web_query_id">
                        <div class="form-group mb-3">
                            <input type="text" id="webcategory_name_query" class="form-control" name="category_name_query" placeholder="Relation Category Name">
                        </div>
                        <div class="form-group mb-3">
                             <input type="text" id="webcall_count_query" class="form-control" name="call_count_query" placeholder="Relation Category Name">
                        </div>
                        <div class="form-group mb-3">
                             <input type="text" id="webdays_back_query" class="form-control" name="days_back_query" placeholder="Relation Category Name">
                        </div>
                        <div class="form-group mb-3 form_otput_web_edit_category_relat">

                        </div>
                        <div class="form-group mb-3 float-end">
                            <button type="submit" class="btn btn-success waves-effect waves-light">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            loadRelationCategory();

            function loadRelationCategory() {
                $.ajax({
                    url: "{{ route('getrelationcats') }}",
                    type: "get",
                    success: function(dataRelatCat) {
                        let htmlCat = "";
                        htmlCat +=
                            `<thead><tr> <th>Sl</th> <th>Category Name</th> <th>Threshold Value</th> <th>Action</th> </tr></thead><tbody>`;
                        let optionHtml = "";
                        optionHtml += '<option value="">Select Category</option>'
                        $('.count_rel_cats').text(dataRelatCat.length);
                        for (let i = 0; i < dataRelatCat.length; i++) {
                            const catData = dataRelatCat[i];
                            optionHtml +=
                                `<option value="${catData.id}">${catData.relation_name}</option>`;
                            htmlCat += `<tr>
                                            <td>${i+1}</td>
                                            <td>${catData.relation_name}</td>
                                            <td>${catData.threshold_value}</td>
                                            <td>
                                                <button type="button" class="btn btn-warning waves-effect waves-light btn-xs edit_rel_cat" id="${catData.id}" cat_name="${catData.relation_name}" thres_value="${catData.threshold_value}"><i class="fas fa-pencil-alt "></i></button>

                                                <button type="button" class="btn btn-danger waves-effect waves-light btn-xs del_rel_cat" id="${catData.id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </td>
                                        </tr>`;
                        }
                        htmlCat += `</thead`;
                        $('#relation_select').html(optionHtml);
                        $('.rel_cat_table').html(htmlCat);
                    }
                });
            }

            $(document).on('click', '.add_new_rel_cat', function(e) {
                e.preventDefault();
                $('#relation_category_modal').modal('show');
            });

            $(document).on('click', '.edit_rel_cat', function(e) {
                $('#level_id').val($(this).attr('id'));
                $('#caregory_name').val($(this).attr('cat_name'));
                $('#threshold_value').val($(this).attr('thres_value'));
                $('#relation_category_modal').modal('show');
            });

            $(document).on('submit', '#relation_cat_form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(saceRelData) {
                        let retSuccHtml = "";
                        if (saceRelData.type == true) {
                            loadRelationCategory();
                            $('#relation_cat_form')[0].reset();
                            retSuccHtml = `<div class="alert alert-succcess alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${saceRelData.message}.
                            </div>`;
                        } else {
                            retSuccHtml = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${saceRelData.message}.
                            </div>`;
                        }
                        $('.form_otput_category_relat').html(retSuccHtml);
                    }
                });
            });

            $(document).on('click', '.del_rel_cat', function(e) {
                e.preventDefault();
                if (confirm("Are you sure to delete?")) {
                    $.ajax({
                        url: "{{ route('deleterelcat') }}?cat_id=" + $(this).attr('id'),
                        type: "get",
                        success: function(delRelData) {
                            alert(delRelData.message);
                            loadRelationCategory();
                        }
                    });
                }

            });

            // assign relation category
            let assignRelTable = $('#relation_category_table').DataTable({
                "processing": true,
                "ajax": "{{ route('alltemplerelation') }}",
                "columns": [{
                        data: 'relation_name',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<button type="button" class="btn btn-warning waves-effect waves-light btn-xs edit_rel_cat_assigned" id="${data.id}" temple_id="${data.user_id}" assign_id="${data.lead_assign_id}"><i class="fas fa-pencil-alt "></i></button>

                            <button type="button" class="btn btn-danger waves-effect waves-light btn-xs del_rel_cat_assigned" id="${data.id}" temple_id="${data.user_id}" assign_id="${data.lead_assign_id}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>`;
                        }
                    }
                ]
            });

            $(document).on('click', '.edit_rel_cat_assigned', function(e) {
                $('#assign_relation_id').val($(this).attr('id'));
                $('#relation_select').val($(this).attr('assign_id'));
                $('#temple_select').val($(this).attr('temple_id'));
                $('#assign_relation_category_modal').modal('show');
            });

            $(document).on('click', '.del_rel_cat_assigned', function(e) {
                if (confirm("Are you sure to delete?")) {
                    $.ajax({
                        url: "{{ route('deleteassignedrelation') }}",
                        type: "get",
                        data: {
                            "relation_select": $(this).attr('assign_id'),
                            "temple_select": $(this).attr('temple_id'),
                            "assign_relation_id": $(this).attr('id')
                        },
                        success: function(delAssRel) {
                            if (delAssRel.type == true) {
                                alert(delAssRel.message);
                                relation.ajax.reload();
                            } else {
                                alert(delAssRel.message);
                            }
                        }
                    });
                }
            });

            $(document).on('submit', '#assign_relation_cat_form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(saceRelData) {
                        let retSuccHtml = "";
                        if (saceRelData.type == true) {
                            relation.ajax.reload();
                            $('#assign_relation_cat_form')[0].reset();
                            retSuccHtml = `<div class="alert alert-succcess alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${saceRelData.message}.
                            </div>`;
                        } else {
                            retSuccHtml = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${saceRelData.message}.
                            </div>`;
                        }
                        $('.form_otput_ass_category_relat').html(retSuccHtml);
                    }
                });
            });


            $(document).on('click', '.assign_new_rel_cat', function(e) {
                e.preventDefault();
                $('#assign_relation_category_modal').modal('show');
            });

            loadAllTemples();

            function loadAllTemples() {
                var temple_html = '<option value="">Select User</option>';
                var temple_id_html = '';
                $.ajax({
                    url: "{{ route('getalltemples') }}",
                    type: "get",
                    success: function(temple_response) {
                        temple_html += `<option value="">Select Temple</option>`;
                        for (let l = 0; l < temple_response.length; l++) {
                            const temple_list = temple_response[l];
                            temple_html +=
                                `<option value="${temple_list.temple_id}">${temple_list.name}</option>`;
                        }
                        $('#temple_select').html(temple_html);
                    }
                });
            }

            loadFacebookData();

            function loadFacebookData() {
                $.ajax({
                    url: "{{ route('getfacebookquery') }}",
                    type: "get",
                    success: function(facebookQueryData) {
                        let htmlFacebookQuery = '';

                        for (let i = 0; i < facebookQueryData.length; i++) {
                            const elementFb = facebookQueryData[i];
                            htmlFacebookQuery += `<tr>
                                                    <td>${elementFb.id}</td>
                                                    <td>${elementFb.relation_name}</td>
                                                    <td>${elementFb.call_count}</td>
                                                    <td>${elementFb.creation_date_time}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning waves-effect waves-light btn-xs edit_fb_query_cat" id="${elementFb.id}" rel_name="${elementFb.relation_name}" call_count="${elementFb.call_count}" days_back="${elementFb.creation_date_time}"><i class="fas fa-pencil-alt "></i></button>
                                                    </td>
                                            </tr>`;
                        }
                        $('.facebook_query_data').html(htmlFacebookQuery);
                    }
                })
            }

            $(document).on('click','.edit_fb_query_cat',function(e){
                e.preventDefault();
                 $("#fb_query_id").val($(this).attr('id'));
                $('#category_name_query').val($(this).attr('rel_name'));
                $('#call_count_query').val($(this).attr('call_count'));
                $('#days_back_query').val($(this).attr('days_back'));
                $('#eqit_query_modal').modal('show');
            });

            loadWebsiteData();

            function loadWebsiteData() {
                $.ajax({
                    url: "{{ route('getwebsitequery') }}",
                    type: "get",
                    success: function(websiteQueryData) {
                        let htmlWebsiteQuery = '';

                        for (let i = 0; i < websiteQueryData.length; i++) {
                            const elementWeb = websiteQueryData[i];
                            htmlWebsiteQuery += `<tr>
                                                    <td>${elementWeb.id}</td>
                                                    <td>${elementWeb.relation_name}</td>
                                                    <td>${elementWeb.call_count}</td>
                                                    <td>${elementWeb.creation_date_time}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning waves-effect waves-light btn-xs edit_web_query_cat" id="${elementWeb.id}" rel_name="${elementWeb.relation_name}" call_count="${elementWeb.call_count}" days_back="${elementWeb.creation_date_time}"><i class="fas fa-pencil-alt "></i></button>
                                                    </td>
                                            </tr>`;
                        }
                        $('.website_query_data').html(htmlWebsiteQuery);
                    }
                })
            }

            $(document).on('click','.edit_web_query_cat',function(e){
                e.preventDefault();
                $("#web_query_id").val($(this).attr('id'));
                $('#webcategory_name_query').val($(this).attr('rel_name'));
                $('#webcall_count_query').val($(this).attr('call_count'));
                $('#webdays_back_query').val($(this).attr('days_back'));
                $('#eqit_web_query_modal').modal('show');
            });

            $(document).on('submit','#edit_query_form',function(e){
                e.preventDefault();
                $.ajax({
                    url : $(this).attr('action'),
                    type : $(this).attr('method'),
                    data : $(this).serialize(),
                    success : function(editQuerySubmit){
                        htmlError = '';
                        if (editQuerySubmit.type==true) {
                            htmlError = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${editQuerySubmit.message}
                            </div>`;
                            $('#edit_query_form')[0].reset();
                            loadFacebookData();
                        } else {
                             htmlError = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${editQuerySubmit.message}
                            </div>`;
                        }
                        $('.form_otput_edit_category_relat').html(htmlError);
                        window.setTimeout(function(){
                            $('.form_otput_edit_category_relat').html('');
                        }, 3000);
                    }
                });
            });

            $(document).on('submit','#edit_web_query_form',function(e){
                e.preventDefault();
                $.ajax({
                    url : $(this).attr('action'),
                    type : $(this).attr('method'),
                    data : $(this).serialize(),
                    success : function(editQuerySubmit){
                        htmlError = '';
                        if (editQuerySubmit.type==true) {
                            htmlError = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${editQuerySubmit.message}
                            </div>`;
                            $('#edit_web_query_form')[0].reset();
                            loadWebsiteData();
                        } else {
                             htmlError = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> ${editQuerySubmit.message}
                            </div>`;
                        }
                        $('.form_otput_web_edit_category_relat').html(htmlError);
                        window.setTimeout(function(){
                            $('.form_otput_edit_category_relat').html('');
                        },3000);

                    }
                });
            });


        });
    </script>
@endsection

@section('page-title', 'Manage Website Plans')
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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Plans</a></li>
                            <li class="breadcrumb-item active add_caste_link">Manage Website Plan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Mange Website Plans</h4>

                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item">
                                <a href="#home-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Plan Category</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                    <span class="d-none d-sm-inline-block">Website Plans</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="home-b2">
                                <div class="row">
                                    <div class="col-md-8 output_msg_cat">

                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success float-end btn-xs btn_add_cat"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <table class="table table-striped table-inverse w-100">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Category Name</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="plan_cat_list">

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane active" id="profile-b2">
                                <div class="row">
                                    <div class="col-md-8 output_msg_plan_cat">

                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-success float-end btn-xs btn_add_plan"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <table class="table table-striped table-inverse w-100">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Plan Name</th>
                                            <th>Plan Type</th>
                                            <th>Category</th>
                                            <th>Content</th>
                                            <th>Contacts</th>
                                            <th>Amount</th>
                                            <th>Discount</th>
                                            <th>Advance Discount</th>
                                            <th>Validity</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="plan_list">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>


    <!-- Category Modal -->
    <div class="modal fade" id="category_model" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Category</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('category_manage') }}" method="post" id="category_form">
                        @csrf
                        <input type="hidden" name="category_id_fet" id="category_id_fet">
                        <div class="form-group mb-3">
                            <input type="text" required name="category_name_filled" id="category_name_filled"
                                class="form-control" placeholder="Category Name">
                        </div>
                        <div class="form-group">
                            <input type="checkbox" value="1" checked name="category_status" class="mb-3"
                                placeholder="Category Name">
                        </div>
                        <div class="form-group cat_msg_output"></div>
                        <div class="form-group">
                            <button class="btn btn-success" type="sutmit" name="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Website Plans --}}
    <div class="modal fade" id="web_plan_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Website Plans</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('postwebplan') }}" method="post" id="plan_form">
                        @csrf
                        <input type="hidden" id="web_plan_id" name="web_plan_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="plan_name" id="plan_name" class="form-control"
                                    placeholder="Plan Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="plan_type" id="plan_type" class="form-control"
                                    placeholder="Plan Type" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <select name="web_plan_category" required id="web_plan_category" class="form-select"></select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="number" name="plan_validity" id="plan_validity" class="form-control"
                                    placeholder="Plan Validity in Months" min="0" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="number" name="plan_amount" required id="plan_amount" placeholder="Plan Amount" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="number" name="plan_discount" required id="plan_discount" placeholder="Plan Discount" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="number" name="advance_dis_amount" required id="advance_dis_amount" placeholder="Advance Discount" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="number" name="plan_credit" required id="plan_credit" placeholder="Whatsapp Point" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <textarea name="plan_content" id="plan_content" class="form-control" placeholder="Plan Description" rows="5"></textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                               <input type="checkbox" checked name="plan_status" value="1" >
                            </div>

                            <div class="col-md-8 mb-3 plan_amt_output">
                            </div>

                            <div class="col-md-12 mb-3">
                               <button class="btn btn-success float-end" name="submit" type="submit">Save</button>
                            </div>

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

            loadWebsitePlans();

            function loadWebsitePlans() {
                let htmlWebList = "";
                $.ajax({
                    url: "{{ route('getwebplans') }}",
                    type: "get",
                    success: function(planList) {
                        for (let i = 0; i < planList.length; i++) {
                            const planData = planList[i];
                            htmlWebList += `<tr>
                                                <td scope="row">${planData.plan_name}</td>
                                                 <td>${planData.plan_type}</td>
                                                <td>${planData.category_name}</td>
                                                <td>${planData.content}</td>
                                                <td>${planData.contacts}</td>
                                                <td>${planData.amount}</td>
                                                <td>${planData.discount}</td>
                                                <td>${planData.advance_discount}</td>
                                                <td>${planData.validity} Months</td>
                                                <td>`;
                            if (planData.status == 1) {
                                htmlWebList +=
                                    `<i class="fa fa-check text-success" aria-hidden="true"></i>`;
                            } else {
                                htmlWebList +=
                                    `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
                            }
                            htmlWebList += `</td>
                                                <td>${planData.created_at}</td>
                                                <td>${planData.updated_at}</td>
                                                <td> <button class="btn btn-warning btn-xs btn_edit_plan" web_plan_id="${planData.id}"><i class="fas fa-pencil-alt"></i></button></td>
                                            </tr>`;
                        }
                        $('.plan_list').html(htmlWebList);
                    }
                })
            }

            window.setTimeout(() => {
                getWebPlanCategory();
            }, 1000);

            function getWebPlanCategory() {
                let htmlWebCatList = "";
                let planCatListOptions = "";
                $.ajax({
                    url: "{{ route('getwebplancats') }}",
                    type: "get",
                    success: function(planCatList) {
                        planCatListOptions += `<option value="">SelectCategory</option>`;
                        for (let i = 0; i < planCatList.length; i++) {
                            const planCatData = planCatList[i];
                            htmlWebCatList += `<tr>
                                    <td>${planCatData.category_name}</td>
                                    <td>${planCatData.created_at}</td>
                                    <td>${planCatData.updated_at}</td>
                                    <td>`;
                            if (planCatData.status == 1) {
                                htmlWebCatList +=
                                    `<i class="fa fa-check text-success" aria-hidden="true"></i>`;
                            } else {
                                htmlWebCatList +=
                                    `<i class="fa fa-times text-danger" aria-hidden="true"></i>`;
                            }
                            htmlWebCatList += `</td>
                                    <td>
                                        <button class="btn btn-warning btn-xs btn_edit_category" plan_cat_id="${planCatData.id}" plan_cat_name="${planCatData.category_name}"><i class="fas fa-pencil-alt"></i></button>
                                    </td>
                                </tr>`;
                            if (planCatData.status == 1) {
                                planCatListOptions +=
                                    `<option value="${planCatData.id}">${planCatData.category_name}</option>`;
                            }
                        }
                        $('#web_plan_category').html(planCatListOptions);
                        $('.plan_cat_list').html(htmlWebCatList);
                    }
                })
            }

            $(document).on('click', '.btn_add_cat', function(e) {
                e.preventDefault();
                $('#category_model').modal('show');
            });

            $(document).on('click', '.btn_edit_category', function(e) {
                e.preventDefault();
                $('#category_id_fet').val($(this).attr('plan_cat_id'));
                $('#category_name_filled').val($(this).attr('plan_cat_name'));
                $('#category_model').modal('show');
            });

            $(document).on('submit', '#category_form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(dataCatsSave) {
                        if (dataCatsSave.type == true) {
                            $('.cat_msg_output').html(`<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Message!</strong> ${dataCatsSave.message}.
                            </div>`);
                            $('#category_form')[0].reset();
                            getWebPlanCategory();
                        }
                        window.setTimeout(function() {
                            $('.cat_msg_output').html('');
                        }, 3000);
                    }
                });
            });

            $(document).on('submit', '#plan_form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(dataPlanSave) {
                        if (dataPlanSave.type == true) {
                            $('.plan_amt_output').html(`<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Message!</strong> ${dataPlanSave.message}.
                            </div>`);
                            $('#plan_form')[0].reset();
                            loadWebsitePlans();
                        }
                        window.setTimeout(function() {
                            $('.plan_amt_output').html('');
                        }, 3000);
                    }
                });
            });

            $(document).on('click', '.btn_add_plan', function() {
                $('#web_plan_modal').modal('show');
            });

            $(document).on('click','.btn_edit_plan',function(e){
                e.preventDefault();
                let planId = $(this).attr('web_plan_id');
                $('#web_plan_id').val(planId);
                $.ajax({
                    url : "{{route('getplanbyids')}}",
                    type : "get",
                    data : {"plan_id":planId},
                    success : function(dataEdit){
                        $('#plan_name').val(dataEdit.plan_name);
                        $('#plan_type').val(dataEdit.plan_type);
                        $('#web_plan_category').val(dataEdit.category);
                        $('#plan_validity').val(dataEdit.validity);
                        $('#plan_amount').val(dataEdit.amount);
                        $('#plan_discount').val(dataEdit.discount);
                        $('#advance_dis_amount').val(dataEdit.advance_discount);
                        $('#plan_credit').val(dataEdit.contacts);
                        $('#plan_content').val(dataEdit.content);
                         $('#web_plan_modal').modal('show');
                    }
                })
            });
        });
    </script>
@endsection


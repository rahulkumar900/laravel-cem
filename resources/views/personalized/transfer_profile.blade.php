@section('page-title', 'Double Approval')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title profileDetails">Transfer Profile</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Sample Profiles</a></li>
                            <li class="breadcrumb-item active">Transfer Profiles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <form action="{{route('transferleadtoteple')}}" method="post" id="transferProfileFrom">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="">Transfer From</label> <span class="text-danger profileCounts" style="display: none">Profile Found : <span class="userProfileCount">80</span></span>
                                        <select name="transfer_from" class="form-select" id="transfer_from">
                                            <option value="">Select Transfer From</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="">Transferable Profiles</label>
                                       <input type="number" class="form-control d-none" name="no_of_profiles" id="no_of_profiles" placeholder="No of Profiles">
                                    </div>
                                    <div class="form-group mb-3 user_list" style="height: 30vh; overflow-y: scroll">

                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="">Transfer To</label>
                                        <select name="transfer_to" class="form-select" id="transfer_to">
                                            <option value="">Select Transfer To</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 outputMessage">
                                    </div>
                                    <div class="form-group mb-3 float-end btnDiv" style="display: none">
                                        <button type="submit" class="btn btn-success">Transfer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    </div>

    <style>
        .img-fluid {
            height: 300px;
        }

    </style>

@endsection
@section('custom-scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ url('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('libs/jquery-datatables-checkboxes/js/dataTables.checkboxes.min.js') }}"></script>
    <script src="{{ url('js/pages/product-list.init.js') }}"></script>
    <script>
        "use strict";
         $(".alert").alert();
        $(document).ready(function() {

            $(document).on('submit','#transferProfileFrom', function(e){
                e.preventDefault();
                $.ajax({
                    url : $(this).attr('action'),
                    type : "post",
                    data : $(this).serialize(),
                    success : function(transferData){
                        if(transferData.type==true){
                           var htmlMsg = `<div class="alert alert-success alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success</strong> ${transferData.message}</div>`;
                            $('.outputMessage').html(htmlMsg);
                            $('#transferProfileFrom')[0].reset();
                             $('.userProfileCount').html('');
                             $('.profileCounts').hide();
                             $('.user_list').html("");
                        }else{
                           var htmlMsg = `<div class="alert alert-danger alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success</strong> Failed to Transfer</div>`;
                            $('.outputMessage').html(htmlMsg);
                        }
                    }
                })
            });

            $(document).on('change','#transfer_from', function(){
                var templeId = $(this).val();
                $.ajax({
                    url : "{{route('gettempleprofiles')}}",
                    type : "get",
                    data : {"temple_id" : templeId},
                    success : function(userData){
                        $('.profileCounts').show();
                        $('.userProfileCount').html(userData.length);
                        $('#no_of_profiles').val(userData.length);
                        if(userData.length>0){
                            let userHtml = '';
                            userHtml += `<ul class="list-group">`;
                            for (let i = 0; i < userData.length; i++) {
                                const userDetails = userData[i];
                                userHtml += `<li class="list-group-item">
                                            <input type="checkbox" name="profile_id[]" value="${userDetails.id}" class="form-control-file" id=""> ${userDetails.name} &nbsp &nbsp (${userDetails.user_mobile})
                                    </li>`;
                            }
                            userHtml += `</ul>`;
                            $('.user_list').html(userHtml);
                            $('.btnDiv').show();
                        }
                    }
                });
            });


            loadTempleIds();
            function loadTempleIds(){
                $.ajax({
                    url : "{{route('getallusers')}}",
                    type : "get",
                    success : function(dataUser){
                        let htmlDta = '<option value="">Select User</option>';
                        for (let i = 0; i < dataUser.data.length; i++) {
                            const userDataArray = dataUser.data[i];
                            htmlDta += `<option value="${userDataArray.temple_id}">${userDataArray.name}</option>`;
                        }
                        $('#transfer_from').html(htmlDta);
                        $('#transfer_to').html(htmlDta);
                    }
                })
            }

            $(document).on('change','#transfer_to', function(){
                var temple_id = $(this).val();
                var temple_id_from = $('#transfer_from').val();
                if(temple_id==temple_id_from){
                    $('.btnDiv').hide();
                }else{
                    $('.btnDiv').show();
                }
            });

        });
    </script>
@endsection


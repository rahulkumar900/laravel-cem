@section('page-title', 'transfer Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Transfer Leads !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Teamleader</a></li>
                            <li class="breadcrumb-item active">Transfer Leads</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-6 offset-md-3">
                            <form action="{{route('savetransferleads')}}" method="post" id="transferLeadForm">
                                @csrf
                                <div class="form-group mb-2">
                                    <label for="">Transfer From</label>
                                    <select name="transfer_from" id="transfer_from" class="form-select" required>
                                        <option value="">Select from</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">Transfer To</label>
                                    <select name="transfer_to" id="transfer_to" class="form-select" required>
                                        <option value="">Select from</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="">No Of Leads <span class="noOfLeads text-danger"></span></label>
                                    <input type="number" name="no_of_leads" id="no_of_leads" min="1" max="150" class="form-control" required>
                                </div>
                                <div class="form-group formOutput"></div>
                                <div class="form-group float-end btnDiv" style="display: none">
                                    <button type="submit" class="btn btn-success">Transfer</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
    <input type="number" id="totalRecordsFound" class="d-none">
@endsection
@section('custom-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // load all users
    loadAllTemples();

    function loadAllTemples() {
        var temple_html = '<option value="">Select User</option>';
        $.ajax({
            url: "{{ route('accessabletempleides') }}",
            type: "get",
            success: function(temple_response) {
                for (let l = 0; l < temple_response.length; l++) {
                    const temple_list = temple_response[l];
                        temple_html +=
                            `<option value="${temple_list.temples.temple_id}">${temple_list.temples.name}</option>`;
                }
                $('#transfer_from').html(temple_html);
                $("#transfer_to").html(temple_html);
            }
        });
    }

    $(document).on('change','#transfer_from', function(){
        $.ajax({
            url : "{{route('counttempleleads')}}",
            type : "get",
            data : {"temple_id":$(this).val()},
            success : function(dataCount){
                htmlCount = $('.noOfLeads').html(`Total ${dataCount} Lead Found`);
                $('#totalRecordsFound').val(dataCount);
            }
        });
    });

    $(document).on('submit','#transferLeadForm', function(e){
        e.preventDefault();
        var transferFrom  =$('#transfer_from').val();
        var transferTo  =$('#transfer_to').val();
        var totalLeads = $('#totalRecordsFound').val();
        var givenLeads = $('#no_of_leads').val();
        if((transferFrom != transferTo) && (totalLeads > 0) && (givenLeads <= totalLeads)){
            $.ajax({
                url : $(this).attr('action'),
                type : $(this).attr('method'),
                data : $(this).serialize(),
                success : function(formResponse){
                    var htmlData = '';
                    if(formResponse.type==true){
                        htmlData += `<div class="alert alert-success" role="alert">
                                        <strong>Success</strong> ${formResponse.message}
                                    </div>`;
                    }else{
                        htmlData += `<div class="alert alert-danger" role="alert">
                                        <strong>Success</strong> ${formResponse.message}
                                    </div>`;
                    }
                    $('.formOutput').html(htmlData);
                    window.setTimeout(() => {
                            $('.formOutput').html('');
                            $('#transferLeadForm')[0].reset();
                    }, 2000);
                }
            });
        }else{
            $('.btnDiv').hide();
             $('.formOutput').html('<h4 class="text-danger">Form Validation Failed Please Check Carefully</h4>');
        }
    });

    $(document).on('change','#transfer_to', function(){
        var transferFrom  =$('#transfer_from').val();
        var transferTo  =$('#transfer_to').val();
        if(transferFrom != transferTo){
            $('.btnDiv').show();
             $('.formOutput').html('');
        }else{
             $('.btnDiv').hide();
             $('.formOutput').html('<h4 class="text-danger">Transfer From and Transfer To Cannot Be Same</h4>');
        }
    });

    $(document).on('blur','#no_of_leads',function(){
        var noOfLeads = $(this).val();
        var totalLeads = $('#totalRecordsFound').val();
        if(totalLeads >= noOfLeads){
             $('.btnDiv').show();
             $('.formOutput').html('');
        }else{
            $('.btnDiv').hide();
             $('.formOutput').html('<h4 class="text-danger">Lead Limit Exceed to Total</h4>');
        }
    });
});
</script>
@endsection

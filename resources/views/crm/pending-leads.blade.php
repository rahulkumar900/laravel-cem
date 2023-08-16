@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Pending Leads</h4>
                        <table class="table table-striped table-inverse" id="pendingLeadsTable">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Last Followup</th>
                                    <th>Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- next follow up modal starts --}}

    <div class="modal fade" id="next_followup_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Next Followup Modal</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addleadsfollowp') }}" method="post" id="lead_followup_form"
                        autocomplete="off">
                        @csrf
                        <input type="text" class="d-none" id="followup_lead_id" name="followup_lead_id">
                        <div class="form-group mb-2">
                            <label for="">Status of Followup</label>
                            <textarea class="form-control" name="followup_status" id="followup_status" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Next Followup Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" name="next_followup_date"
                                id="next_followup_date" min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d', strtotime('+14 days')) }}" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Select Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select">
                                <option value="">Select Plan</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Interest Level</label>
                            <select name="lead_speed" id="lead_speed" class="form-select">
                                <option value="">Interest Level</option>
                                <option value="Very High" selected>Very High</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                                <option value="Less">Less</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 followup_message">
                        </div>
                        <div class="form-group mb-2">
                            <button class="btn btn-success" type="submit" name="submit">Add Followup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- next follow up modal endss --}}

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            let requstedFbLeads = $('#pendingLeadsTable').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('pendingleadsdata') }}",
                "columns": [
                    {
                        data: null,
                        render: function(data) {
                            if (data.user_data && data.user_data != null) {
                                return data.user_data.name;
                            }else if(data.name != null){
                                return data.name;
                            }else{
                                return "N.A";
                            }
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.user_data) {
                                return data.user_data.user_mobile;
                            }else{
                                return parseInt(data.mobile);
                            }
                        }
                    },
                    {
                        data: 'followup_call_on',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var htmlData = "";
                            htmlData +=
                                `<a data-toggle="collapse" data-target="#dataId${data.id}">View</a>`;
                            let splitComm = data.comments.split(";");
                            for (let i = 0; i < splitComm.length; i++) {
                                const comment = splitComm[i];
                                htmlData +=
                                    `<div id="dataId${data.id}" class="collapse">${comment}</div>`;
                            }
                            return htmlData;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.user_data) {
                                return `<button type="button"
                            class="btn btn-sm btn-warning add_next_followup waves-effect waves-light"
                            data-toggle="tooltip" data-placement="top"
                            title="Add Next Followup" mobile="${data.user_data.user_mobile}" id="${data.user_data_id}">
                            <i class="fas fa-calendar-check"></i></button>`;
                            }
                        }
                    }
                ]
            });

            //add_next_followup
            $(document).on('click', '.add_next_followup', function(e) {
                e.preventDefault();
                $('#followup_lead_id').val($(this).attr('lead_id'));
                $('.followup_message').html('');
                $('#followup_lead_id').val($(this).attr('id'));
                $('#next_followup_modal').modal('show');
            });

            // saving lead form
            $(document).on('submit', '#lead_followup_form', function(e) {
                e.preventDefault();
                var followup_html = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(data_followup) {
                        if (data_followup.type == true) {
                            followup_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> Followup Has Been Saved
                            </div>`;
                            requstedFbLeads.ajax.reload();
                            $('#lead_followup_form')[0].reset();
                        } else {
                            followup_html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Alert!</strong> Failed to Save Record
                            </div>`;
                        }
                        $('.followup_message').html(followup_html);
                    }
                });
            });

            // get crm lead plans
            getCrmPlans();

            function getCrmPlans() {
                plan_optins = `<option value="">Select Plan</option>`;
                $.ajax({
                    url: "{{ route('crmleadplans') }}",
                    type: "get",
                    success: function(plan_resp) {
                        for (let i = 0; i < plan_resp.length; i++) {
                            const plan_amounts = plan_resp[i];
                            var plan_name = plan_amounts.type.split("_");
                            plan_optins +=
                                `<option value="${plan_name[0]}">${plan_name[0]}</option>`;
                        }
                        $('#plan_id').html(plan_optins);
                    }
                });
            }
        });
    </script>
@endsection

@section('page-title', 'Profile Not Sent Today')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title profileDetails">Profile To Be Send Today</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Find Match</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body sentProfileList">
                        <table class="table table-striped table-inverse" id="userlisttable">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Plan Purchase Date</th>
                                    <th>Validity</th>
                                    <th>Find Match</th>
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
    <div class="modal fade" id="validutyUpdatModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Validity and Other Data</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updateuservalidity') }}" method="post" id="updateValidity">
                        @csrf
                        <input type="number" class="form-control d-none" name="user_data_id" id="user_data_id">
                        <div class="form-group row mb-3">
                            <div class="col-md-6"><label for="">Roka Amount</label></div>
                            <div class="col-md-6">
                                <input type="number" class="form-control" id="roka_amtount" name="roka_amount">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <div class="col-md-6"><label for="">Extended Validity (in months)</label></div>
                            <div class="col-md-6">
                                <input type="number" class="form-control" id="validity_month" name="validity_month">
                            </div>
                            <span class="text-muted text-small">Validity Will Extend From Today's Date</span>
                        </div>
                        <div class="form-group row mb-3 outputMessage">
                        </div>
                        <div class="form-group row mb-3">
                            <button type="submit" name="submit" class="btn btn-sm btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .img-fluid {
            height: 300px;
        }
    </style>
    <div class="modal fade" id="dayUpdatModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile Setnt Day</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updtepropfilesentday') }}" method="post" id="dayUpdateForm">
                        @csrf
                        <input type="number" class="d-none" id="dayUpdateUserId" name="user_id">
                        <div class="form-group  row mb-3">
                            <div class="col-md-6"><label for="">Profile Sent Day</label></div>
                            <div class="col-md-6">
                                <select name="sent_day" class="form-select dayProfile">
                                    <option value="">Select</option>
                                    @php
                                        $currentDate = date('Y-m-d');
                                        $nextDate = date('Y-m-d');
                                        
                                        for ($i = 0; $i < 8; $i++) {
                                            $dayOfWeek = date('l', strtotime("+$i days"));
                                            $displayDate = date('F j', strtotime("+$i days"));
                                        
                                            echo "<option value=\"$nextDate\">$dayOfWeek ($displayDate)</option>";
                                        
                                            // Increment the nextDate for the next iteration
                                            $nextDate = date('Y-m-d', strtotime('+1 day', strtotime($nextDate)));
                                        }
                                    @endphp
                                </select>
                            </div>
                        </div>
                        <div class="form-group dayUpdateMessage">

                        </div>
                        <div class="form-group float-end">
                            <button type="submit" name="submit" class="btn btn-purple btn-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ url('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            $(document).on('submit', '#dayUpdateForm', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(dayResponseUpdate) {
                        var dayHtml = '';
                        if (dayResponseUpdate.type == true) {
                            dayHtml =
                                `<div class="alert alert-success" role="alert"><strong>Alert</strong> ${dayResponseUpdate.message}</div>`;
                            window.setTimeout(function() {
                                $('.dayUpdateMessage').html('');
                            }, 1500);
                        } else {
                            dayHtml =
                                `<div class="alert alert-danger" role="alert"><strong>Alert</strong> ${dayResponseUpdate.message}</div>`;
                        }
                        $('.dayUpdateMessage').html(dayHtml);
                    }
                })
            });
            $(document).on('click', '.updateDay', function(e) {
                e.preventDefault();
                $('#dayUpdateUserId').val($(this).attr('userId'));
                var profileSentDay = $(this).attr('profileSentDay');
                if (profileSentDay != null) {
                    $('.dayProfile').val(profileSentDay);
                }
                $('#dayUpdatModal').modal('show')
            });
            var databaseTable = $('#userlisttable').DataTable({
                "order": [
                    [3, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('todaysentlist') }}",
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: 'amount_collected_date',
                    },
                    {
                        data: 'validity',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var urlSend = '{{ route('findmatch') }}';
                            urlSend = urlSend.replace(':appointment', $('#followup_id').val());
                            return `<a href="${urlSend}?user_id=${data.id}&user_birth=${data.birth_date}" target="_blank">Find Match</a>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {

                            return `<button class="btn btn-xs btn-purple updateDay" userId="${data.id}"  profileSentDay="${data.profile_sent_day}" data-toggle="tooltip" data-placement="top" title="ProfileSent Day"><i class="fa fa-user" aria-hidden="true"></i></button>
                               `;
                        }
                    }
                ]
            });
        });
    </script>
@endsection

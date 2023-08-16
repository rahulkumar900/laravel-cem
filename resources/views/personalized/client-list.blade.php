@section('page-title', 'Double Approval')
@extends('layouts.main-landingpage')
@section('page-content')
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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
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
                                        <th>Mobile</th>
                                        <th>Gender</th>
                                        <th>Residance</th>
                                        <th>Manglik</th>
                                        <th>Reg Date</th>
                                        <th>Valid Till</th>
                                        <th>Amount Collected</th>
                                        <th>Roka Amount</th>
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

    {{-- validity modal starts --}}
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
    {{-- validity modal ends --}}

    {{-- day update modal starts --}}

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
    {{-- day upadte modal ends --}}
    @php
        $title = 'Edit Details';
    @endphp
    @include('form.modelProgressForm')
@endsection
@section('custom-scripts')
    <script>
        $(document).ready(function() {
            var databaseTable = $('#user-list-table').DataTable({
                "order": [
                    [6, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('myuserlist') }}",
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: 'user_mobile',
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
                        data: null,
                        render: function(data) {
                            let yourDate = new Date(data.created_at)
                            return yourDate.toISOString().split('T')[0];
                        }
                    },
                    {
                        data: 'validity',
                    },
                    {
                        data: 'amount_collected',
                    },
                    {
                        data: 'roka_charge',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var returnHtml = '<div class="d-flex">';

                            returnHtml +=
                                `<button class="btn btn-xs btn-warning editValidity" userId="${data.user_id}" validMonths="${data.validity_month}" validTill="${data.validity}" amountColleted="${data.amount_collected}" rokaCharges="${data.roka_charge}" profileSentDay="${data.profile_sent_day}" data-toggle="tooltip" data-placement="top" title="Edit Validity"><i class="fa fa-eye" aria-hidden="true"></i></button> &nbsp

                                <button class="btn btn-xs btn-purple updateDay" userId="${data.user_id}"  profileSentDay="${data.profile_sent_day}" data-toggle="tooltip" data-placement="top" title="ProfileSent Day"><i class="fa fa-user" aria-hidden="true"></i></button> &nbsp
                                <a class="btn btn-xs btn-pink" target="_blank" href="{{ route('pdfprofiles') }}?user_ids=${data.user_id}" userId="${data.user_id}"  profileSentDay="${data.profile_sent_day}" data-toggle="tooltip" data-placement="top" title="Download Pdf"><i class="fas fa-file-pdf"></i></a>
                                `;

                            if (new Date(data.validity) > new Date()) {
                                returnHtml +=
                                    `&nbsp<button class="btn btn-xs btn-success viewProfile" userId="${data.user_id}" userDOB="${data.birth_date}" data-toggle="tooltip" data-placement="top" title="Find Match"><i class="fa fa-share" aria-hidden="true"></i></button>`;
                            }
                            returnHtml += '</div>';
                            return returnHtml;
                        }
                    }
                ]
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

            $(document).on('click', '.editValidity', function(e) {
                e.preventDefault();
                $('#user_data_id').val($(this).attr('userId'));
                $('#roka_amtount').val($(this).attr('rokaCharges'));
                $('#validity_month').val($(this).attr('validMonths'));
                $('#validutyUpdatModal').modal('show');
            });

            $(document).on('click', '.viewProfile', function(e) {
                e.preventDefault();
                var userId = $(this).attr('userId');
                var userDOB = $(this).attr('userDOB');
                window.open(`find-match?user_id=${userId}&user_birth=${userDOB}`);
            });

            $(document).on('submit', '#updateValidity', function(e) {
                e.preventDefault();
                var messageHtml = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(submitResponse) {
                        if (submitResponse.type == true) {
                            messageHtml = `<div class="alert alert-success" role="alert">
                                                <strong>Success</strong> ${submitResponse.message}
                                            </div>`;
                            $('#updateValidity')[0].reset();
                            window.setTimeout(() => {
                                databaseTable.ajax.reload();
                                $('.modal').modal('hide');
                            }, 900);
                        } else {
                            messageHtml = `<div class="alert alert-danger" role="alert">
                                                <strong>Alert</strong> ${submitResponse.message}
                                            </div>`;
                        }
                        $('.outputMessage').html(messageHtml);
                        window.setTimeout(() => {
                            $('.outputMessage').html('');
                            databaseTable.ajax.reload();
                        }, 800);
                    }
                });
            });

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
        });
    </script>

@endsection

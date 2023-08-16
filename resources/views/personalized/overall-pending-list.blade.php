@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Overall Update Pending</h4>
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-pane premiumMettingLists row" id="home">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>


    {{-- update status modals starts --}}
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User Status</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updatepremiummeetingstatus') }}" method="post" id="premiumMeetingForm">
                        @csrf
                        <input type="number" name="user_id" id="user_id" class="d-none" value="{{ $_GET['user_id'] }}">
                        <input type="number" name="matched_user_id" id="matched_user_id" class="d-none">
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Select Status</label>
                            <select name="user_status" class="col-md-8 form-select" id="user_status" required>
                                <option value="">Select</option>
                                <option value="0">Negative</option>
                                <option value="2">Pending</option>
                                <option value="3">Meeting</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Next Update Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+5days')) }}" name="next_update"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3 meetingDiv" style="display: none">
                            <label for="" class="col-md-4">Meeting Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+5days')) }}" name="meeting_date"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Comment</label>
                            <textarea name="comments" id="" class="col-md-8 form-control" required></textarea>
                        </div>
                        <div class="row mb-3 formOutputMeetingStatus">

                        </div>
                        <div class="row mb-3">
                            <button type="submit" name="submit" class="btn btn-warning btn-sm">Save Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- update status modal ends --}}


    {{-- update meeting status modals starts --}}
    <div class="modal fade" id="updateMeetingStatusModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User Status</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updatepremiummeetingstatus') }}" method="post" id="premiumMeetingStatusForm">
                        @csrf
                        <input type="number" name="user_id" id="user_id" class="d-none" value="{{ $_GET['user_id'] }}">
                        <input type="number" name="matched_user_id" id="matched_user_id_premium" class="d-none">
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Select Status</label>
                            <select name="user_status" class="col-md-8 form-select" id="user_status" required>
                                <option value="">Select</option>
                                <option value="4">Yes</option>
                                <option value="5">No</option>
                                <option value="2">Pending</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Next Update Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+5days')) }}" name="next_update"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3 meetingDiv" style="display: none">
                            <label for="" class="col-md-4">Meeting Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+5days')) }}" name="meeting_date"
                                class="col-md-8 form-control">
                        </div>
                        <div class="row mb-3">
                            <label for="" class="col-md-4">Comment</label>
                            <textarea name="comments" id="" class="col-md-8 form-control" required></textarea>
                        </div>
                        <div class="row mb-3 formOutputPremiumMeetingStatus">

                        </div>
                        <div class="row mb-3">
                            <button type="submit" name="submit" class="btn btn-warning btn-sm">Save Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- update meeting status modal ends --}}

    {{-- user notes starts --}}
    <div class="modal fade" id="userNotesModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">User Notes</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('saveusernotes') }}" method="post" id="userNotesForm">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="text" name="user_id" value="{{ $_GET['user_id'] }}" class="d-none">
                                    <textarea name="user_comments" id="user_comments" class="form-control" required></textarea>
                                </div>
                                <div class="form-group commentOutput"></div>
                                <div class="form-group float-end">
                                    <button class="btn btn-success waves-effect waves-light" type="submit"
                                        name="submit">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12 mt-3 userNotesData" style="height: 30vh; overflow:scroll">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- user notes ends --}}
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ url('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            loadSentProfileList();

            function loadSentProfileList() {
                var userId = "{{ $_GET['user_id'] }}";
                var sendProfileHtml = '';
                $('.sentProfileList').html(
                    '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
                );
                $.ajax({
                    url: "{{ route('sentprofilefromusermatch') }}",
                    type: "get",
                    data: {
                        "user_id": userId
                    },
                    success: function(sendProfileLoadingResponse) {
                        sendProfileHtml += `<div class="row">`;
                        for (let v = 0; v < sendProfileLoadingResponse.length; v++) {
                            const sendData = sendProfileLoadingResponse[v];

                            sendProfileHtml += `<div class="col-md-6 col-xl-3 div${sendData.user_id}">
                                    <div class="card product-box">

                                        <div class="product-img">
                                            <div class="p-3 text-center">
                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${sendData.photo}"
                                                    alt="profile-pic" class="img-fluid">
                                            </div>
                                            <div class="product-action">
                                                <div class="d-flex">
                                                    <a href="javascript: void(0);"
                                                        class="btn btn-purple d-block action-btn m-2" profileId="${sendData.user_id}" actionType="1"><i
                                                            class="ri-edit-2-fill align-middle"></i>
                                                        Yes</a>

                                                        <a href="javascript: void(0);"
                                                        class="btn btn-danger d-block action-btn m-2" profileId="${sendData.user_id}" actionType="0"><i
                                                            class="ri-edit-2-fill align-middle"></i>
                                                        No</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="product-info border-top p-3">
                                            <div>
                                                <h6 class="font-16 mt-0 mb-1"><a
                                                        href="#"
                                                        class="text-dark">${sendData.marital_status}</a>
                                                </h6>
                                                <p class="text-muted">
                                                    ${sendData.caste}
                                                </p>
                                                <h4 class="m-0"> <span
                                                        class="text-muted"> Name : ${sendData.name}</span>
                                                </h4>
                                            </div>

                                        </div> <!-- end product info-->

                                    </div>
                                </div>`;
                        }
                        sendProfileHtml += `</div>`;
                        $('.premiumMettingLists').html(sendProfileHtml);
                    }
                });
            }

            // update actions
            $(document).on('click', '.action-btn', function(e) {
                e.preventDefault();
                var userId = $(this).attr('profileid');
                var actionType = $(this).attr('actiontype');
                $.ajax({
                    url: "{{ route('updateuseraction') }}",
                    type: "get",
                    data: {
                        "match_id": userId,
                        "action": actionType,
                        "user_id": "{{ $_GET['user_id'] }}"
                    },
                    success: function(actionResponse) {
                        alert(actionResponse.message);
                        $('.div' + userId).hide();
                        $('.sent_profile_count').text(parseInt($('.sent_profile_count')
                            .text()) - 1);
                    }
                });
            });

            $(document).on('click', '.updateStatusBtn', function(e) {
                e.preventDefault();
                $('#matched_user_id').val($(this).attr('matchId'));
                $('.formOutputMeetingStatus').html('');
                $('#updateStatusModal').modal('show');
            });

            // upadate premium meeting status
            $(document).on('click', '.updatePremiumStatusBtn', function(e) {
                e.preventDefault();
                $('#matched_user_id_premium').val($(this).attr('matchId'));
                $('.formOutputMeetingStatus').html('');
                $('#updateMeetingStatusModal').modal('show');
            });

            $(document).on('submit', '#premiumMeetingForm', function(e) {
                e.preventDefault();
                var htmlMesage = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(meetingUpdateResponse) {
                        if (meetingUpdateResponse.type == true) {
                            htmlMesage =
                                '<div class="alert alert-success" role="alert"><strong>Message</strong> ' +
                                meetingUpdateResponse.message + '</div>';
                            $('#premiumMeetingForm')[0].reset();
                            showYesPending();
                            premiumMeetings();
                            window.setTimeout(function() {
                                $('#updateStatusModal').modal('hide');
                                $('.formOutputMeetingStatus').html('');
                            }, 1500);
                        } else {
                            htmlMesage =
                                '<div class="alert alert-primary" role="alert"><strong>Alert</strong> ' +
                                meetingUpdateResponse.message + '</div>';
                        }
                        $('.formOutputMeetingStatus').html(htmlMesage);
                    }
                });
            });

            //premiumMeetingStatusForm
            $(document).on('submit', '#premiumMeetingStatusForm', function(e) {
                e.preventDefault();
                var htmlMesage = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(meetingUpdateResponse) {
                        if (meetingUpdateResponse.type == true) {
                            htmlMesage =
                                '<div class="alert alert-success" role="alert"><strong>Message</strong> ' +
                                meetingUpdateResponse.message + '</div>';
                            $('#premiumMeetingForm')[0].reset();
                            premiumMeetings();
                            window.setTimeout(function() {
                                $('#updateStatusModal').modal('hide');
                                $('.formOutputMeetingStatus').html('');
                            }, 1500);
                        } else {
                            htmlMesage =
                                '<div class="alert alert-primary" role="alert"><strong>Alert</strong> ' +
                                meetingUpdateResponse.message + '</div>';
                        }
                        $('.formOutputPremiumMeetingStatus').html(htmlMesage);
                    }
                });
            });
        });
        // shipla narani 9
    </script>
@endsection

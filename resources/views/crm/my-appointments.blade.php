@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Appointment List
                            {{ date('d-M-Y', strtotime('-5 days')) }}-{{ date('d-M-Y') }}</h4>
                        <div class="row">
                            @foreach ($appointment_data as $appointment)
                                <div class="col-md-3 p-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4>Name : {{ $appointment->userDetails->name }}</h4>
                                            <h4>Mobile : {{ $appointment->userDetails->user_mobile }}</h4>
                                            <h5>Meeting With : {{ $appointment->appointWith->name }}</h5>
                                            <h6>Meeting Date :
                                                {{ $appointment->appointment_date }}-{{ $appointment->appointment_time }}
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button class="btn btn-sm btn-warning addCommentBtn"
                                                        id="{{ $appointment->id }}" type="button">Comment</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-sm btn-primary" data-toggle="collapse"
                                                        data-target="#conetnt{{ $appointment->id }}">Show</button>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="conetnt{{ $appointment->id }}" class="collapse">
                                                        @php
                                                            $exploded_data = explode(';', $appointment->note);
                                                            for ($i = 0; $i < count($exploded_data); $i++) {
                                                                echo '<p>' . $exploded_data[$i] . '</p>';
                                                            }
                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <p>

                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
    {{-- fix aoppintment modal starts --}}

    <div class="modal fade" id="fix_appointment_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Appoinement Note</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="appointmentForm" autocomplete="off">
                        @csrf
                        <input type="number" id="followup_id" name="followup_id" class="d-none">
                        <input type="number" class="d-none form-control" readonly name="user_data_id" id="user_data_id">
                        <div class="col-md-12">
                            {{-- <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Date</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="appoinemtnt_date" id="appoinemtnt_date"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Time</label>
                                <div class="col-md-9">
                                    <input type="time" class="form-control" name="appoinemtnt_time" id="appoinemtnt_time"
                                        value="{{ date('H:i:s') }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">With</label>
                                <div class="col-md-9">
                                    <select name="appoinemtn_with" id="appoinemtn_with" class="form-select">

                                    </select>
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label" for="userName1">Note</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="note" id="note"
                                        placeholder="Meeting Note" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 appointment_output"></div>
                                <div class="col-md-6 appointment_btn_div">
                                    <button type="submit" name="submit" class="btn btn-sm btn-warning">Save
                                        Comment</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- fix aoopintment modal  ends --}}
@endsection
@section('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {


            loadAllTemples();

            function loadAllTemples() {
                var temple_html = '<option value="">Select User</option>';
                var login_user = "{{ Auth::user()->temple_id }}";
                var temple_id_html = '';
                $.ajax({
                    url: "{{ route('getalltemples') }}",
                    type: "get",
                    success: function(temple_response) {
                        for (let l = 0; l < temple_response.length; l++) {
                            const temple_list = temple_response[l];
                            if (temple_list.temple_id == login_user) {
                                temple_html +=
                                    `<option selected="selected" value="${temple_list.temple_id}">${temple_list.name}</option>`;
                                temple_id_html +=
                                    `<option selected="selected" value="${temple_list.id}">${temple_list.name}</option>`;
                            } else {
                                temple_html +=
                                    `<option value="${temple_list.temple_id}">${temple_list.name}</option>`;
                                temple_id_html +=
                                    `<option value="${temple_list.id}">${temple_list.name}</option>`;
                            }
                        }
                        $("#appoinemtn_with").html(temple_id_html);
                    }
                });
            }

            $(document).on('click', '.addCommentBtn', function(e) {
                e.preventDefault();
                $('#followup_id').val($(this).attr('id'));
                $('#fix_appointment_modal').modal('show');
            });

            $(document).on('submit', '#appointmentForm', function(e) {
                e.preventDefault();
                var urlSend = '{{ route('addappointmentnotes', ':appointment') }}';
                urlSend = urlSend.replace(':appointment', $('#followup_id').val());
                $.ajax({
                    url: urlSend,
                    type : "post",
                    data : $(this).serialize(),
                    success : function(appointformResponse){
                        if(appointformResponse.type==true){
                            $('.appointment_output').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Success</strong> ${appointformResponse.message}
                            </div>`);
                            window.setTimeout(() => {
                                    window.location.reload();
                            }, 2000);
                        }else{
                            $('.appointment_output').html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Warning</strong> ${appointformResponse.message}
                            </div>`);
                        }
                    }
                });
            });
            $(".alert").alert();
        });
    </script>
@endsection

@section('page-title', 'Manage Caste')
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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Failed Transaction</a></li>
                            <li class="breadcrumb-item active add_caste_link">Manage</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Manage Failed Transaction</h4>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse " id="caste-list-table">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Day</th>
                                        <th>Team Leader</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($temple_logs as $temple_data)
                                        <tr>
                                            <td>{{ $temple_data->day }}</td>
                                            <td>{{ $temple_data->team_leader }}</td>
                                            <td>{{ $temple_data->created_at }}</td>
                                            <td>{{ $temple_data->updated_at }}</td>
                                            <td><button class="btn btn-xs btn_edit btn-warning" id="{{ $temple_data->id }}"
                                                    day="{{ $temple_data->day }}"
                                                    team_leader="{{ $temple_data->team_leader }}"><i
                                                        class="fas fa-pencil-alt"></i></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="team_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Access</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('saveacceslogs') }}" method="post" id="add_txn_form">
                        @csrf
                        <input type="hidden" id="txn_id" name="txn_id">
                        <div class="form-group form_output mb-3">
                            <select name="team_leaders" id="team_leaders" class="form-select">
                                <option value="">Select Team Leader</option>
                            </select>
                        </div>
                        <div class="form-group form_output mb-3">
                            <select name="team_day" id="team_day" class="form-select">
                                <option value="">Select Team Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="form-group submit_div">
                        </div>
                        <div class="form-group float-right  float-end">
                            <button class="btn btn-sm btn-danger text-right float-end" type="submit"
                                name="submit">Update</button>
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

            $(document).on('click', '.btn_edit', function(e) {
                e.preventDefault();
                $('#team_modal').modal('show');
                $('#team_leaders').val($(this).attr('team_leader'));
                $('#txn_id').val($(this).attr('id'));
                $('#team_day').val($(this).attr('day'));
            });

            loadTeamLeader();

            function loadTeamLeader() {
                html_list = '';
                $.ajax({
                    url: "{{ route('listteamleader') }}",
                    type: "get",
                    success: function(dataLeader) {
                        html_list += `<option value="">Select Team Leader</option>`;

                        for (let i = 0; i < dataLeader.length; i++) {
                            const leaders = dataLeader[i];
                            html_list +=
                                `<option value="${leaders.name}">${leaders.name}</option>`;
                        }
                        $('#team_leaders').html(html_list);
                    }
                });
            }

            $(document).on('submit', '#add_txn_form', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(data) {
                        htmlMsg = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>Message!</strong> ${data.message}.
                        </div>`;
                        $('.submit_div').html(htmlMsg);
                        $('#add_txn_form')[0].reset();
                        window.location.reload();
                    }
                })
            });
        });
    </script>
@endsection

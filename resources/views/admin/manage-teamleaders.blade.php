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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Others</a></li>
                            <li class="breadcrumb-item active">Manage Temleader</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" style="overflow: scroll">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="header-title">Team Leader</h4>
                            </div>
                            <div class="col-md-4">
                                @if (Auth::user()->role==9)
                                    <select name="team_leaders" id="team_leaders" class="form-select">
                                    <option value="">Select Team Leader</option>
                                </select>
                                @endif
                            </div>
                            <div class="col-md-12 output_message mt-2"></div>
                            <div class="col-md-6 mt-3">
                                {{-- free team --}}
                                <form action="{{ route('updateteamleaderlist') }}" method="post" id="open_team_form">
                                    @csrf
                                    <input type="hidden" name="add_team" value="1">
                                    <input type="hidden" name="temple_id" id="open_temple_id">
                                    <ul class="list-group open_team_div" style="height: 60vh; overflow-x: scroll">

                                    </ul>

                                    <ul class="list-group ">
                                        <button type="submit" name="submit" class="btn btn-xs btn-success open_team_btn"
                                            style="display: none"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Add</button>
                                    </ul>
                                </form>

                            </div>
                            <div class="col-md-6 mt-3">
                                {{-- my team --}}
                                <form action="{{ route('updateteamleaderlist') }}" method="post" id="my_team_form">
                                    @csrf
                                    <input type="hidden" name="temple_id" id="mine_temple_id">
                                    <input type="hidden" name="remove_team" value="1">
                                    <ul class="list-group my_team_div" style="height: 60vh; overflow-x: scroll">

                                    </ul>
                                    <ul class="list-group ">
                                        <button type="submit" name="submit" class="btn btn-xs btn-danger my_team_btn"
                                            style="display: none"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                            Remove</button>
                                    </ul>
                                </form>
                            </div>
                        </div>


                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>


@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        "<?php  if(Auth::user()->role==9){ ?>"
        loadTeamLeaders();
        "<?php  } ?>"

        function loadTeamLeaders() {
            html_list = '';
            $.ajax({
                url: "{{ route('listteamleader') }}",
                type: "get",
                success: function(dataLeader) {
                    html_list += `<option value="">Select Team Leader</option>`;

                    for (let i = 0; i < dataLeader.length; i++) {
                        const leaders = dataLeader[i];
                        html_list += `<option value="${leaders.temple_id}">${leaders.name}</option>`;
                    }
                    $('#team_leaders').html(html_list);
                }
            });
        }

        $(document).ready(function() {
            $(document).on('change', '#team_leaders', function() {
                loadTeamList($(this).val());
                $('#open_temple_id').val($(this).val());
                $('#mine_temple_id').val($(this).val());
            });
        });

        function loadTeamList(temple_id) {
            $.ajax({
                url: "{{ route('getteamlists') }}",
                data: {
                    'temple_id': temple_id
                },
                type: "get",
                success: function(dataLeader) {
                    let openTeamHtml = ``;
                    let myTeam = ``;

                    for (let i = 0; i < dataLeader.team_list.length; i++) {
                        const openTeams = dataLeader.team_list[i];
                        openTeamHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                            <input class="form-check-input me-1" type="checkbox" name="open_team_ids[]" value="${openTeams.temple_id}"
                                aria-label="...">
                            ${openTeams.name}
                            <span class="badge bg-pink rounded-pill">${openTeams.mobile}</span>
                        </li>`;
                    }
                    $('.open_team_btn').show();
                    $('.open_team_div').html(openTeamHtml);


                    for (let j = 0; j < dataLeader.my_team.length; j++) {
                        const myTeams = dataLeader.my_team[j];
                        myTeam += `<li class="list-group-item d-flex justify-content-between align-items-center">
                            <input class="form-check-input me-1" type="checkbox" name="my_team_ids[]" value="${myTeams.temple_id}"
                                aria-label="...">
                            ${myTeams.name}
                            <span class="badge bg-success rounded-pill">${myTeams.mobile}</span>
                        </li>`;
                    }
                    $('.my_team_btn').show();
                    $('.my_team_div').html(myTeam);
                }
            });
        }

        $(document).on('submit', '#open_team_form', function(e) {
            e.preventDefault();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(dataSave) {
                    let return_html = "";
                    if (dataSave.type == true) {
                        return_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>Success!</strong> ${dataSave.message}.
                        </div>`;
                    } else {
                         return_html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>Alert! </strong> ${dataSave.message}.
                        </div>`;
                    }
                    $('.output_message').html(return_html);
                    loadTeamList($('#team_leaders').val());
                }
            })
        });

        $(document).on('submit', '#my_team_form', function(e) {
            e.preventDefault();
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(dataSaveRem) {
                    let return_html = "";
                    if (dataSaveRem.type == true) {
                        return_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>Success!</strong> ${dataSaveRem.message}.
                        </div>`;
                    } else {
                         return_html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>Alert !</strong> ${dataSaveRem.message}.
                        </div>`;
                    }
                    $('.output_message').html(return_html);
                    loadTeamList($('#team_leaders').val());
                }
            })
        });
    </script>
@endsection

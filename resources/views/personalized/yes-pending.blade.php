@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Overall Yes Pending and Update Pending</h4>
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-bordered nav-justified">
                                        <li class="nav-item">
                                            <a href="#home-b2" data-bs-toggle="tab" aria-expanded="false"
                                                class="nav-link active">
                                                <span class="d-inline-block d-sm-none"><i
                                                        class="mdi mdi-home-variant"></i></span>
                                                <span class="d-none d-sm-inline-block">Overall Yes Pending</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="true"
                                                class="nav-link">
                                                <span class="d-inline-block d-sm-none"><i
                                                        class="mdi mdi-account"></i></span>
                                                <span class="d-none d-sm-inline-block">Overall Update Pending</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="home-b2">
                                            <table class="table table-striped" id="yesPending" width="100%">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Mobile</th>
                                                        <th>Find Match</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="profile-b2">
                                            <table class="table table-striped" id="overAllPending" width="100%">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Mobile</th>
                                                        <th>Find Match</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ url('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            // first table
            var databaseTable = $('#yesPending').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('yespendinglist') }}",
                "columns": [
                    {
                        data: 'name',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<a href="{{route('overallyesuserpendinglist')}}?user_id=${data.id}&user_birth=${data.birth_date}">Show List</a>`;
                        }
                    }
                ]
            });

            var databaseOverallTable = $('#overAllPending').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('overallpendinglist') }}",
                "columns": [
                    {
                        data: 'name',
                    },
                    {
                        data: 'user_mobile',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var urlSend = '{{ route('overalluserpendinglist') }}';
                            urlSend = urlSend.replace(':appointment', $('#followup_id').val());
                            return `<a href="${urlSend}?user_id=${data.user_data_id}&user_birth=${data.birth_date}">Show List</a>`;
                        }
                    }
                ]
            });
        });
    </script>
@endsection

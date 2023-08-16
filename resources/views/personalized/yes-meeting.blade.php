@section('page-title', 'Overall Yes Meeting')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Overall Yes Meeting</h4>
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped table-inverse w-100" id="yes_meeting_list" >
                                        <thead class="thead-inverse">
                                            <tr>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Caste</th>
                                                <th>Marital Status</th>
                                                <th>Followup At</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
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
            var databaseTable = $('#yes_meeting_list').DataTable({
                "order": [
                    [4, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('overallyesmeetinglist') }}",
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: 'mobile',
                    },
                    {
                        data: 'caste',
                    },
                    {
                        data: 'marital_status',
                    },
                    {
                        data: null,
                        render : function(data){
                            return data.followup_call_on ?? "NA";
                        }
                    },
                    {
                        data: null,
                        render : function(data){
                            return `<a class="btn btn-xs btn-info" href="{{route('findmatch')}}?user_id=${data.user_id}&user_birth=${data.birth_date}" target="_new">View</a>`;
                        }
                    }

                ]
            });
        });
    </script>
@endsection

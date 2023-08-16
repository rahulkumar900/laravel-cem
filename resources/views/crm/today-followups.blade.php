@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Today's Followup ({{ date('d-M-Y') }})</h4>
                        <table class="table table-bordered" id="todaysfollowupdatatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Interest Level</th>
                                    <th>Next Followup</th>
                                    <th>Comment</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            const templeId = localStorage.getItem('temple_id');
            let requstedFbLeads = $('#todaysfollowupdatatable').DataTable({
                "order": [
                    [2, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('todaysfollowupdata') }}",
                "columns": [{
                        data: null,
                        render: function(data) {
                            return data.user_data.name;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.user_data.user_mobile;
                        }
                    },
                    {
                        data: 'speed',
                    },
                    {
                        data: 'followup_call_on',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.comments
                        }
                    }
                ]
            });
        });
    </script>
@endsection

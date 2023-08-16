@section('page-title', 'Leads Management')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Operator Calls !</h4>
                    <div class="page-title-left">
                        {{-- <a href="#"
                            class="btn btn-sm btn-bordered btn-purple search_lead waves-effect waves-light float-right">Overall
                            Search</a> --}}
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">CRM</a></li>
                            <li class="breadcrumb-item active">Operator Call List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <h4 class="header-title">Call List</h4>
                    {{-- datatable --}}
                    <table id="salescrm-table" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Interest</th>
                                <th>Assigned At</th>
                                <th>Profile</th>
                                <th>Contact</th>
                                <th>Lead Created</th>
                                <th>Followup Call</th>
                                <th>Enquiry Date</th>
                                <th>Comments</th>
                                <th>Plan Pitch</th>
                                <th>Appointments</th>
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


@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection

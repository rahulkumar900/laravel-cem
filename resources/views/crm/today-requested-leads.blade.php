@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Requested Leads {{date('d-M-Y')}}</h4>

                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item">
                                <a href="#home-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link active link1">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span class="d-none d-sm-inline-block">Facebook</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link link3">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                    <span class="d-none d-sm-inline-block">Exhaust Leads</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#settings-b3" data-bs-toggle="tab" aria-expanded="false" class="nav-link link0">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                    <span class="d-none d-sm-inline-block">Website Leads</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active div1" id="home-b2">
                                {{-- facebook leads --}}
                                <div class="row">
                                    <div class="col-md-12 displaydiv1">
                                        <table class="table" id="requestedFbLeads">
                                            <tr>
                                                <th>Mobile</th>
                                                <th>Requested At</th>
                                                <th>Status</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane div3" id="settings-b2">
                                {{-- exhaust leads --}}
                                <div class="row">
                                    <div class="col-md-12 displaydiv3">
                                         <table class="table table-bordered" id="requestedExhaustLeads" style="width: 100%">
                                            <tr>
                                                <th>Mobile</th>
                                                <th>Requested At</th>
                                                <th>Status</th>
                                            </tr>
                                    </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane div0" id="settings-b3">
                                {{-- website leads --}}
                                <div class="row">
                                    <table class="table table-bordered" id="requestedWebLeads" style="width: 100%">
                                            <tr>
                                                <th>Mobile</th>
                                                <th>Requested At</th>
                                                <th>Status</th>
                                            </tr>
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
    <script>
        $(document).ready(function() {
            const templeId = localStorage.getItem('temple_id');
            let requstedFbLeads = $('#requestedFbLeads').DataTable({
                "order": [
                    [2, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('allrequestedfbleads') }}",
                "columns": [
                    {
                        data: 'user_phone',
                    },
                    {
                        data: 'request_by_at',
                    },
                    {
                        data: null,
                        render : function(data){
                            if(data.request_by==templeId){
                                return '<span class="text-success">Not-Pcked Up</span>';
                            }else{
                                return '<span class="text-danger">Not-Pcked Up</span>';
                            }
                        }
                    }
                ]
            });

            let requstedWebLeads = $('#requestedWebLeads').DataTable({
                "order": [
                    [2, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('allrequestedwebleads') }}",
                "columns": [
                    {
                        data: 'mobile',
                    },
                    {
                        data: 'updated_at',
                    },
                    {
                        data: null,
                        render : function(data){
                            if(data.request_by==templeId){
                                return '<span class="text-success">'+data.request_by+'</span>';
                            }else{
                                return '<span class="text-danger">'+data.request_by+'</span>';
                            }
                        }
                    }
                ]
            });

            let requstedExhaustLeads = $('#requestedExhaustLeads').DataTable({
                "order": [
                    [2, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('allrequestedexhaustleads') }}",
                "columns": [
                    {
                        data: 'mobile',
                    },
                    {
                        data: 'updated_at',
                    },
                    {
                        data: null,
                        render : function(data){
                            if(data.request_by==templeId){
                                return '<span class="text-success">'+data.request_by+'</span>';
                            }else{
                                return '<span class="text-danger">'+data.request_by+'</span>';
                            }
                        }
                    }
                ]
            });


        });
    </script>
@endsection

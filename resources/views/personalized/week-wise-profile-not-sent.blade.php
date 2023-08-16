@section('page-title', 'Weekly Profile Not Sent')
@extends('layouts.main-landingpage')
@php
    $date_monday = date('Y-m-d', strtotime('monday this week'));
    
    // first date range
    $monday_to_oneday = date('Y-m-d', strtotime('-1 day', strtotime($date_monday)));
    $monday_to_eightday = date('Y-m-d', strtotime('-8 day', strtotime($date_monday)));
    
    // second date range
    $monday_to_oneday_s = date('Y-m-d', strtotime('-1 day', strtotime($monday_to_eightday)));
    $monday_to_eightday_s = date('Y-m-d', strtotime('-8 day', strtotime($monday_to_eightday)));
    
    // second date range
    $monday_to_oneday_t = date('Y-m-d', strtotime('-1 day', strtotime($monday_to_eightday_s)));
    $monday_to_eightday_t = date('Y-m-d', strtotime('-8 day', strtotime($monday_to_eightday_s)));
    
    // second date range
    $monday_to_oneday_f = date('Y-m-d', strtotime('-1 day', strtotime($monday_to_eightday_t)));
    $monday_to_eightday_f = date('Y-m-d', strtotime('-8 day', strtotime($monday_to_eightday_t)));
    
@endphp
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title profileDetails">Profile To Be Send Today</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Sent Profiles</a></li>
                            <li class="breadcrumb-item active">Weekly Not Sent</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- navigatin starts --}}
                        <ul class="nav nav-tabs nav-bordered nav-justified">
                            <li class="nav-item">
                                <a href="#week_four" data-bs-toggle="tab" aria-expanded="false" div_id="week_four"
                                    class="nav-link dayWiseDataNav"
                                    dateRange="{{ $monday_to_eightday_t }}/{{ $monday_to_oneday_t }}">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span
                                        class="d-none d-sm-inline-block">{{ date('d-M', strtotime($monday_to_eightday_t)) }}
                                        to {{ date('d-M', strtotime($monday_to_oneday_t)) }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#week_three" data-bs-toggle="tab" div_id="week_three" aria-expanded="true"
                                    class="nav-link dayWiseDataNav"
                                    dateRange="{{ $monday_to_eightday_s }}/{{ $monday_to_oneday_s }}">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                    <span
                                        class="d-none d-sm-inline-block">{{ date('d-M', strtotime($monday_to_eightday_s)) }}
                                        to till {{ date('d-M', strtotime($monday_to_oneday_s)) }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#week_two" data-bs-toggle="tab" div_id="week_two" aria-expanded="false"
                                    class="nav-link dayWiseDataNav"
                                    dateRange="{{ $monday_to_oneday }}/{{ $monday_to_eightday }}">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-email-variant"></i></span>
                                    <span
                                        class="d-none d-sm-inline-block">{{ date('d-M', strtotime($monday_to_eightday)) }}
                                        to {{ date('d-M', strtotime($monday_to_oneday)) }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#week_one" data-bs-toggle="tab" div_id="week_one" aria-expanded="false"
                                    class="nav-link active dayWiseDataNav"
                                    dateRange="{{ $monday_to_oneday }}/{{ date('Y-m-d') }}">
                                    <span class="d-inline-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                    <span
                                        class="d-none d-sm-inline-block">{{ date('d-M-y', strtotime('monday this week')) }}
                                        to till now</span>
                                </a>
                            </li>
                        </ul>
                        {{-- navigation ends --}}

                        {{-- content body starts --}}
                        <div class="tab-content">
                            <div class="tab-pane" id="week_four">
                            </div>
                            <div class="tab-pane" id="week_three">
                            </div>
                            <div class="tab-pane" id="week_two">
                            </div>
                            <div class="tab-pane active" id="week_one">
                            </div>
                        </div>
                        {{-- content body ends --}}
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    <style>
        .img-fluid {
            height: 300px;
        }
    </style>
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
@endsection
@section('custom-scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="{{ url('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script>
        "use strict";
        $(document).ready(function() {
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
            $(document).on('click', '.updateDay', function(e) {
                e.preventDefault();
                $('#dayUpdateUserId').val($(this).attr('userId'));
                var profileSentDay = $(this).attr('profileSentDay');
                if (profileSentDay != null) {
                    $('.dayProfile').val(profileSentDay);
                }
                $('#dayUpdatModal').modal('show')
            });
            $(document).on('click', '.dayWiseDataNav', function() {
                var dateRange = $(this).attr('dateRange');
                var linkUrl = $(this).attr('div_id');
                loadData(dateRange, linkUrl);
            });

            var dateRange = "{{ $monday_to_eightday }}/{{ $monday_to_oneday }}";
            var linkUrl = "";
            loadData(dateRange, "week_one");

            function loadData(dateRange, linkUrl) {
                $.ajax({
                    url: "{{ route('weeklyprofilenotsentdata') }}",
                    type: "get",
                    data: {
                        "date_range": dateRange,
                        "url_link": linkUrl
                    },
                    success: function(resonseData) {
                        if (resonseData.type == true) {
                            var tableHtmlData = '';
                            tableHtmlData += `<table class="table table-striped table-inverse">
                                                <thead class="thead-inverse">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Gender</th>
                                                        <th>Profile Sent</th>
                                                        <th>Validity</th>
                                                        <th>Find Match</th>
                                                        <th>Action</th>
                                                    </tr>
                                            </thead>`;
                            for (let i = 0; i < resonseData.data_table.length; i++) {
                                const tableData = resonseData.data_table[i];
                                //var countTotal = tableData.order_list.split(",");
                                tableHtmlData += `<tbody>
                                                    <tr>
                                                        <td scope="row">${tableData.name}</td>
                                                        <td>${tableData.gender}</td>
                                                        <td>${tableData.profile_sent}</td>
                                                        <td>${tableData.validity}</td>
                                                        <td>`;
                                var urlSend = '{{ route('findmatch') }}';
                                urlSend = urlSend.replace(':findmatch', $('#followup_id').val());
                                tableHtmlData +=
                                    `<a href="${urlSend}?user_id=${tableData.id}&user_birth=${tableData.birth_date}" target="_blank">Find Match</a>`;
                                tableHtmlData += `</td>` +
                                    `<td> <button class="btn btn-xs btn-purple updateDay" userId="${tableData.id}"  profileSentDay="${tableData.profile_sent_day}" data-toggle="tooltip" data-placement="top" title="ProfileSent Day"><i class="fa fa-user" aria-hidden="true"></i></button> &nbsp
                              </td>`


                                    +
                                    `
                                                    </tr>
                                                </tbody>`;
                            }
                            tableHtmlData += `</table>`;

                            $('#' + linkUrl).html(tableHtmlData);
                        }
                    }
                })
            }
        });
    </script>
@endsection

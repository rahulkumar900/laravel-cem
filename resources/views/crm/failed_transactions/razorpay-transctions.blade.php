@section('page-title', 'Razorpay Transaction')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">
	<input type="hidden" value="{{$month_start}}" id="month_start" />
	<input type="hidden" value="{{$week_start}}" id="week_start" />
	<input type="hidden" value="{{$today_date}}" id="today_date" />
	<input type="hidden" value="{{ date('d-m-Y', strtotime('-6 days'))}}" id="last_seven_days" />
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Razorpay Transaction View</h4>
                        <div class="row">
                            <div class="col-xl-12 col-md-12 p-3 bg-white rounded shadow mb-5">
                                <ul class="nav nav-tabs nav-pills flex-column flex-sm-row text-center bg-light border-0 rounded-nav"
                                    role="tablist" data-tabs="tabs">
                                    <li class="nav-item flex-sm-fill">
                                        <a href="{{ route('razorpayview') }}"
                                            class="nav-link border-0 text-uppercase font-weight-bold active">
                                            Razorpay View
                                        </a>
                                    </li>
                                    <li class="nav-item flex-sm-fill">
                                        <a href="{{ route('paytmview') }}"
                                            class="nav-link border-0 text-uppercase font-weight-bold">
                                            Paytm View
                                        </a>
                                    </li>
                                    <li class="nav-item flex-sm-fill">
                                        <a href="{{ route('failedtransactions') }}"
                                            class="nav-link border-0 text-uppercase font-weight-bold">
                                            Failed Transaction View
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>

        <div class="row">
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-4 mb-4">
                <a href="javascript:void(0)">
                    <div class="card border-left-info shadow h-80 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Count
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $count }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons text-gray-300" style="font-size: 43px;">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-4 mb-4">
                <a href="javascript:void(0)">
                    <div class="card border-left-primary shadow h-80 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Automated count
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $automated_count }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons text-gray-300" style="font-size: 43px;">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-4 mb-4">
                <a href="javascript:void(0)">
                    <div class="card border-left-warning shadow h-80 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Assisted Count
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $assisted_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons text-gray-300" style="font-size: 43px;">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-4 mb-4">
                <a href="javascript:void(0)">
                    <div class="card border-left-warning shadow h-80 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Success Count
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $success_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons text-gray-300" style="font-size: 43px;">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-4 mb-4">
                <a href="javascript:void(0)">
                    <div class="card border-left-warning shadow h-80 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Failed Count
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $fail_count }}</div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons text-gray-300" style="font-size: 43px;">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Filter Section</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12" style="margin-bottom: 20px;">
                            <ul id="myTabs" class="nav nav-pills nav-justified" role="tablist" data-tabs="tabs"
                                style="justify-content: center;">
                                <li>
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="getLast24HoursData()">
                                        <i class="fas fa-search fa-sm"></i>
                                        Last 24 Hours
                                    </a>
                                </li>
                                <li style="margin-left: 20px;">
                                    <a href="javascript:void(0);" class="btn btn-primary"
                                        onclick="getLastThreeDaysData()">
                                        <i class="fas fa-search fa-sm"></i>
                                        Last Three Days
                                    </a>
                                </li>
                                <li style="margin-left: 20px;">
                                    <a href="javascript:void(0);" class="btn btn-primary"
                                        onclick="getLastSevenDaysData()">
                                        <i class="fas fa-search fa-sm"></i>
                                        Last Seven Days
                                    </a>
                                </li>
                                <li style="margin-left: 20px;">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="getLast1MonthsData()">
                                        <i class="fas fa-search fa-sm"></i>
                                        This Month
                                    </a>
                                </li>
                            </ul>
                            <form method="get" action="{{ route('razorfilterview') }}" id="lastThreeForm"
                                hidden="">
                                {{ csrf_field() }}
                                <input type="hidden" name="from" value="{{ date('Y-m-d', strtotime('-2 days')) }}">
                                <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                            </form>

                            <form method="get" action="{{ route('razorfilterview') }}" id="last24Form"
                                hidden="">
                                {{ csrf_field() }}
                                <input type="hidden" name="from" value="{{ date('Y-m-d') }}">
                                <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                            </form>

                            <form method="get" action="{{ route('razorfilterview') }}" id="last7Form"
                                hidden="">
                                {{ csrf_field() }}
                                <input type="hidden" name="from" value="{{ date('Y-m-d', strtotime('-6 days')) }}">
                                <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                            </form>

                            <form method="get" action="{{ route('razorfilterview') }}" id="last1Month"
                                hidden="">
                                {{ csrf_field() }}
                                <input type="hidden" id="frd" name="from" value="{{ date('Y-m-d') }}">
                                <input type="hidden" id="tod" name="to" value="{{ date('Y-m-d') }}">
                            </form>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <form class="form-inline" method="get" action="{{ route('razorfilterview') }}">
                                    {{ csrf_field() }}
                                    <ul id="myTabs" class="nav nav-pills nav-justified" role="tablist"
                                        data-tabs="tabs" style="justify-content: center;">
                                        <li>
                                            @if (empty($from))
                                                <input type="date" class="form-control" id="from"
                                                    name="from">
                                            @else
                                                <input value="{{ $from }}" type="date" class="form-control"
                                                    id="from" name="from">
                                            @endif
                                        </li>
                                        <li style="margin-left: 20px;">
                                            <a href="javascript:void(0);">
                                                @if (empty($to))
                                                    <input type="date" class="form-control" id="to"
                                                        name="to" style="margin-left: 20px;">
                                                @else
                                                    <input value="{{ $to }}" type="date"
                                                        class="form-control" id="to" name="to"
                                                        style="margin-left: 20px;">
                                                @endif
                                            </a>
                                        </li>
                                        <li style="margin-left: 20px;">
                                            <button class="btn btn-primary" type="submit" style="margin-left: 20px;">
                                                <i class="fas fa-search fa-sm"></i> Search
                                            </button>
                                        </li>
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="col-md-12 d-block" style="margin: 20px 0;">
                            <div class="text-right">
                                <label style="margin: 0 20px;">Filter by Status</label>
                                <select class="form-select" style="width: 20%;">
                                    <option value="">All</option>
                                    <option value="captured">Success</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered filter-table-data" width="100%" cellspacing="0"
                                id="razorpayList">
                                <thead>
                                    <tr data-type="captured failed all">
                                        <th>Sr. No</th>
                                        <th>Payment Way</th>
                                        <th>Assign To</th>
                                        <th>Assign By</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Method</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 0)
                                    @foreach ($result as $payment)
                                        {{-- removed failed transactions due to task assigned to meraj on 15-07-2021 --}}
                                        @if ($payment['status'] != 'failed')
                                            @php($i++)
                                            @php($status = $payment['status'])
                                            <tr class="search"
                                                @if ($status == 'captured') data-type='captured' @elseif($status == 'failed') data-type='failed' @else data-type='all' @endif>
                                                <td>{{ $i }}</td>
                                                @if ($payment['order_id'] == null)
                                                    <td>Automated</td>
                                                @else
                                                    <td>Assisted</td>
                                                @endif
                                                <td>{{ $payment['assign_to'] }}</td>
                                                <td>{{ $payment['assign_by'] }}</td>
                                                <td>{{ $payment['contact'] }}</td>
                                                <td>{{ $payment['email'] }}</td>
                                                <td>{{ $payment['amount'] / 100 }}</td>
                                                <td>{{ $payment['status'] }}</td>
                                                <td>{{ $payment['method'] }}</td>
                                                <td>{{ date('d M Y h:i:s', $payment['created_at']) }}</td>
                                                <td>
                                                    <button class="btn btn-success btn-sm"
                                                        onclick="showPaymentDetail('{{ $payment['id'] }}')">View
                                                        Detail</button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- The Modal -->
        <div class="modal fade" id="paymentDetail">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="btn-close" data-dismiss="modal"</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <table id="detailPayment" style="width: 100%;">
                            <tr>
                                <th>Payment Id</th>
                                <td class="pay_id"></td>

                                <th>Amount</th>
                                <td class="amount"></td>
                            </tr>

                            <tr>
                                <th>Currency</th>
                                <td class="currency"></td>

                                <th>Status</th>
                                <td class="status"></td>
                            </tr>

                            <tr>
                                <th>Method</th>
                                <td class="method"></td>

                                <th>Amount Refunded</th>
                                <td class="amount_refunded"></td>
                            </tr>

                            <tr>
                                <th>Captured</th>
                                <td class="captured"></td>

                                <th>Description</th>
                                <td class="description"></td>
                            </tr>

                            <tr>
                                <th>Error Code</th>
                                <td class="error_code"></td>

                                <th>Error Description</th>
                                <td class="error_description"></td>
                            </tr>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $('.filter-handle').on('change', function(e) {
            // retrieve the dropdown  selected value
            var location = e.target.value;
            var table = $('.filter-table-data');
            // if a location is selected
            if (location.length) {
                // hide all not matching
                table.find('tr.search[data-type!=' + location + ']').hide();
                // display all matching
                table.find('tr.search[data-type=' + location + ']').show();
            } else {
                // location is not selected, display all
                table.find('tr.search').show();
            }
        });

        $('th').click(function() {
            var table = $(this).parents('table').eq(0)
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
            this.asc = !this.asc
            if (!this.asc) {
                rows = rows.reverse()
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i])
            }
        });

        function comparer(index) {
            return function(a, b) {
                var valA = getCellValue(a, index),
                    valB = getCellValue(b, index)
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
            }
        }

        function getCellValue(row, index) {
            return $(row).children('td').eq(index).text();
        }

        function showPaymentDetail(id) {

            $.ajax({
                url: "{{route('getrazoraydetails')}}/" + id,
                method: 'GET',
                success: function(data) {

                    $('.pay_id').html(data.id);
                    $('.amount').html(data.amount);
                    $('.currency').html(data.currency);
                    $('.status').html(data.status);
                    $('.method').html(data.method);
                    $('.amount_refunded').html(data.amount_refunded);
                    $('.captured').html(data.captured);
                    $('.description').html(data.description);
                    $('.error_code').html(data.error_code);
                    $('.error_description').html(data.error_description);
                    $('.modal-title').html("Payment Id :- " + data.id);
                    $('#paymentDetail').modal('show');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function getLastThreeDaysData() {
            document.getElementById('from').value = '{{ date('Y-m-d', strtotime('-2 days')) }}';
            document.getElementById('to').value = document.getElementById('today_date').value;
            document.getElementById('lastThreeForm').submit();
        }

        function getLast24HoursData() {
            document.getElementById('from').value = document.getElementById('today_date').value;
            document.getElementById('to').value = document.getElementById('today_date').value;
            document.getElementById('last24Form').submit();
        }

        function getLastSevenDaysData() {
            document.getElementById('from').value = '{{ date('Y-m-d', strtotime('-6 days')) }}';
            document.getElementById('to').value = document.getElementById('today_date').value;
            document.getElementById('last7Form').submit();
        }

        function getLast1MonthsData() {
            document.getElementById('from').value = document.getElementById('month_start').value;
            document.getElementById('frd').value = document.getElementById('month_start').value;
            document.getElementById('to').value = document.getElementById('today_date').value;
            document.getElementById('tod').value = document.getElementById('today_date').value;
            var a = document.getElementById('month_start').value;
            document.getElementById('last1Month').submit();
        }
    </script>
@endsection

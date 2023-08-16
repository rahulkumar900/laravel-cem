@section('page-title', 'RZP Failed Transaction')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">
        <input type="hidden" value="{{ $month_start }}" id="month_start" />
        <input type="hidden" value="{{ $week_start }}" id="week_start" />
        <input type="hidden" value="{{ $today_date }}" id="today_date" />
        <input type="hidden" value="{{ date('d-m-Y', strtotime('-6 days')) }}" id="last_seven_days" />
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Transactions</h4>
                        <div class="row">
                            <div class="col-xl-12 col-md-12 p-3 bg-white rounded shadow mb-5">
                                <ul class="nav nav-tabs nav-pills flex-column flex-sm-row text-center bg-light border-0 rounded-nav"
                                    role="tablist" data-tabs="tabs">
                                    <li class="nav-item flex-sm-fill">
                                        <a href="{{ route('razorpayview') }}"
                                            class="nav-link border-0 text-uppercase font-weight-bold">
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
                                            class="nav-link border-0 text-uppercase font-weight-bold active">
                                            Failed Transaction View
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card shadow mb-4" style="width: 100%;">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Filter Section</h6>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 text-center">
                                        <ul id="myTabs" class="nav nav-pills nav-justified" role="tablist"
                                            data-tabs="tabs" style="justify-content: center; margin-bottom: 10px;">
                                            <li>
                                                <a href="javascript:void(0);" class="btn btn-primary"
                                                    onclick="getLast24HoursData()">
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
                                                <a href="javascript:void(0);" class="btn btn-primary"
                                                    onclick="getLast1MonthsData()">
                                                    <i class="fas fa-search fa-sm"></i>
                                                    This Month
                                                </a>
                                            </li>
                                        </ul>
                                        <form method="GET" action="{{ route('razorfilterview') }}" id="lastThreeForm"
                                            hidden="">
                                            <!-- {{ csrf_field() }} -->
                                            <input type="hidden" name="from"
                                                value="{{ date('Y-m-d', strtotime('-2 days')) }}">
                                            <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                                        </form>

                                        <form method="GET" action="{{ route('razorfilterview') }}" id="last24Form"
                                            hidden="">
                                            <!-- {{ csrf_field() }} -->
                                            <input type="hidden" name="from" value="{{ date('Y-m-d') }}">
                                            <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                                        </form>

                                        <form method="GET" action="{{ route('razorfilterview') }}" id="last7Form"
                                            hidden="">
                                            <!-- {{ csrf_field() }} -->
                                            <input type="hidden" name="from"
                                                value="{{ date('Y-m-d', strtotime('-6 days')) }}">
                                            <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                                        </form>

                                        <form method="GET" action="{{ route('razorfilterview') }}" id="last1Month"
                                            hidden="">
                                            <!-- {{ csrf_field() }} -->
                                            <input type="hidden" id="frd" name="from"
                                                value="{{ date('Y-m-d') }}">
                                            <input type="hidden" id="tod" name="to"
                                                value="{{ date('Y-m-d') }}">
                                        </form>
                                    </div>

                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('razorfilterview') }}">
                                            <div class="row">
                                                <!-- {{ csrf_field() }} -->
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        @if (empty($from))
                                                            <input type="date" class="form-control" id="from"
                                                                name="from">
                                                        @else
                                                            <input value="{{ $from }}" type="date"
                                                                class="form-control" id="from" name="from">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        @if (empty($to))
                                                            <input type="date" class="form-control" id="to"
                                                                name="to" style="margin-left: 20px;">
                                                        @else
                                                            <input value="{{ $to }}" type="date"
                                                                class="form-control" id="to" name="to"
                                                                style="margin-left: 20px;">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <button class="btn btn-primary" type="submit"
                                                            style="margin-left: 20px;">
                                                            <i class="fas fa-search fa-sm"></i> Search
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <!-- DataTales Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h4 class="m-0 font-weight-bold text-primary">
                                            Failed Transaction View ({{ $count }})
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12" style="margin: 20px auto;">
                                            <form method="GET">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <label>Assign By</label>
                                                            <select class="form-control" name="assign_by"
                                                                style="margin-left: 20px;">
                                                                <option value="">--Filter By--</option>
                                                                <option value="admin"
                                                                    @if (@$_GET['sort_by_source'] == 'admin')  @endif>Admin</option>
                                                                <optgroup label="Temples">
                                                                    @foreach ($temples as $temple)
                                                                        <option value="{{ $temple['temple_id'] }}"
                                                                            @if (@$_GET['assign_by'] == $temple['temple_id']) {{ 'selected' }} @endif>
                                                                            {{ $temple['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                                <optgroup label="Moderators">
                                                                    @foreach ($moderators as $moderator)
                                                                        <option value="{{ $moderator['temple_id'] }}"
                                                                            @if (@$_GET['assign_by'] == $moderator['temple_id']) {{ 'selected' }} @endif>
                                                                            {{ ucfirst($moderator['name']) }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="input-group">
                                                            <label>Assign To</label>
                                                            <select class="form-control" name="assign_to"
                                                                style="margin-left: 20px;">
                                                                <option value="">--Filter By--</option>
                                                                <option value="admin"
                                                                    @if (@$_GET['sort_by_source'] == 'admin')  @endif>Admin</option>
                                                                <optgroup label="Temples">
                                                                    @foreach ($temples as $temple)
                                                                        <option value="{{ $temple['temple_id'] }}"
                                                                            @if (@$_GET['assign_to'] == $temple['temple_id']) {{ 'selected' }} @endif>
                                                                            {{ $temple['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                                <optgroup label="Moderators">
                                                                    @foreach ($moderators as $moderator)
                                                                        <option value="{{ $moderator['temple_id'] }}"
                                                                            @if (@$_GET['assign_to'] == $moderator['temple_id']) {{ 'selected' }} @endif>
                                                                            {{ ucfirst($moderator['name']) }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered filter-table-data" width="100%"
                                                cellspacing="0" id="razorpayList">
                                                <thead>
                                                    <tr data-type="all TXN_SUCCESS TXN_FAILURE">
                                                        <th>Sr. No</th>
                                                        <th>Name</th>
                                                        <th>Assign By</th>
                                                        <th>Assign To</th>
                                                        <th>Order ID</th>
                                                        <th>Customer ID</th>
                                                        <th>Contact</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th>Narration</th>
                                                        <th>Txn Date / Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($i = 0)
                                                    @foreach ($result as $payment)
                                                        <tr class="search">
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $payment->name }}</td>
                                                            <td>
                                                                {{ $payment->asssybn_by_name ?? '' }}
                                                            </td>
                                                            <td>
                                                                {{ $payment->asssybn_to_name ?? '' }}
                                                            </td>
                                                            <td>{{ $payment->order_id }}</td>
                                                            <td>{{ $payment->customer_id }}</td>
                                                            <td>{{ $payment->customer_mobile }}</td>
                                                            <td>{{ $payment->amount }}</td>
                                                            <td>{{ $payment->status }}</td>
                                                            <td>{{ $payment->narration }}</td>
                                                            <td>
                                                                {{ date('d M Y', strtotime($payment->created_at)) }} /
                                                                {{ date('h:i:s', strtotime($payment->created_at)) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <center>
                                                {{ $result->appends(request()->query())->render() }}
                                            </center>
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

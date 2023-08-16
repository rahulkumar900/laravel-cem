@section('page-title', 'Paytm Transaction')
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
                                            class="nav-link border-0 text-uppercase font-weight-bold active">
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
                                        <form method="post" action="{{ route('paytmfilterview') }}" id="lastThreeForm"
                                            hidden="">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="from"
                                                value="{{ date('Y-m-d', strtotime('-2 days')) }}">
                                            <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                                        </form>

                                        <form method="post" action="{{ route('paytmfilterview') }}" id="last24Form"
                                            hidden="">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="from" value="{{ date('Y-m-d') }}">
                                            <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                                        </form>

                                        <form method="post" action="{{ route('paytmfilterview') }}" id="last7Form"
                                            hidden="">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="from"
                                                value="{{ date('Y-m-d', strtotime('-6 days')) }}">
                                            <input type="hidden" name="to" value="{{ date('Y-m-d') }}">
                                        </form>

                                        <form method="post" action="{{ route('paytmfilterview') }}" id="last1Month"
                                            hidden="">
                                            {{ csrf_field() }}
                                            <input type="hidden" id="frd" name="from"
                                                value="{{ date('Y-m-d') }}">
                                            <input type="hidden" id="tod" name="to"
                                                value="{{ date('Y-m-d') }}">
                                        </form>
                                    </div>

                                    <div class="col-md-12 text-center">
                                        <form method="post" action="{{ route('paytmfilterview') }}">
                                            {{ csrf_field() }}
                                            <div class="row">
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
                                            Paytm Transaction View ({{ $count }})
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12" style="margin: 20px 0;">
                                            <div class="text-right">
                                                <label style="margin: 0 20px;">Filter by Status</label>
                                                <select class="filter-handle form-select" style="width: 20%;">
                                                    <option value="">All</option>
                                                    <option value="TXN_SUCCESS">Success</option>
                                                    <option value="TXN_FAILURE">Failed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered filter-table-data" width="100%"
                                                cellspacing="0" id="razorpayList">
                                                <thead>
                                                    <tr data-type="all TXN_SUCCESS TXN_FAILURE">
                                                        <th>Sr. No</th>
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
                                                        @if ($payment['status'] == 'TXN_SUCCESS')
                                                            @php($i++)
                                                            @php($status = $payment['status'])
                                                            <tr class="search"
                                                                @if ($status == 'TXN_SUCCESS') data-type='TXN_SUCCESS' @elseif($status == 'TXN_FAILURE') data-type='TXN_FAILURE' @else data-type='all' @endif>
                                                                <td>{{ $i }}</td>
                                                                <td>{{ $payment['assign_to'] }}</td>
                                                                <td>{{ $payment['assign_by'] }}</td>
                                                                <td>{{ $payment['contact'] }}</td>
                                                                <td>{{ $payment['email'] }}</td>
                                                                <td>
                                                                    @if ($payment['amount'] == '')
                                                                        0.00
                                                                    @else
                                                                        {{ $payment['amount'] }}
                                                                    @endif
                                                                </td>
                                                                <td>{{ $payment['status'] }}</td>
                                                                <td>{{ $payment['method'] }}</td>
                                                                <td>{{ date('d M Y h:i:s', strtotime($payment['created_at'])) }}
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-success btn-sm"
                                                                        @if ($payment['order_id'] != null || $payment['order_id'] != '' || $payment['order_id'] != 'null') onclick="showPaymentDetail('{{ $payment['order_id'] }}')" @else onclick="showPaymentDetail('{{ $payment['id'] }}')" @endif>View
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
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
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

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

        function showPaymentDetail(id) {
            // alert(id);
            $.ajax({
                url: "{{ route('viewpaytmdetails') }}/" + id,
                method: 'GET',
                success: function(data) {
                    // alert(data.amount);
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

@section('page-title', 'HRMS-Employees')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Attendance Report !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">HRMS</a></li>
                            <li class="breadcrumb-item active">Att. Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <select name="month_name" id="month_name" class="form-select">
                    @php
                        for ($i = 0; $i < 12; $i++) {
                            $time = strtotime(sprintf('%d months', $i));
                            $label = date('F', $time);
                            $value = date('n', $time);
                            if ($value < 10) {
                                $value = '0' . $value;
                            }
                            echo "<option value='$value'>$label</option>";
                        }
                    @endphp
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select name="year" id="year" class="form-select">
                    @for ($i = 2018; $i <= date('Y'); $i++)
                        @if ($i == date('Y'))
                            <option value="{{ $i }}" selected>{{ $i }}</option>
                        @else
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endif
                    @endfor
                </select>
            </div>
            <div class="col-md-4 mb-3 button-div">
                <button class="btn btn-sm btn-warning btn-wave btnSearchAtt waves-effect waves-dark"
                    type="button">Search</button>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" style="overflow: scroll">
                        <h4 class="header-title">Employees List</h4>

                        <table id="salescrm-table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Total Hour</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody class="tablelist">
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div>
        </div>
    </div>



@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btnSearchAtt', function(e) {
                e.preventDefault();
                $('.button-div').html(
                    `<button class="btn btn-warning" type="button" disabled=""><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Loading...</button>`
                    );
                let yearNo = $('#year').val();
                let monthNo = $('#month_name').val();
                if (monthNo != "" || yearNo != "") {
                    $.ajax({
                        url: "{{ route('getattreport') }}",
                        type: "get",
                        data: {
                            month_name: monthNo,
                            year_no: yearNo
                        },
                        success: function(attReportResp) {
                            let htmnlData = ``;
                            for (let i = 0; i < attReportResp.length; i++) {
                                const respData = attReportResp[i];
                                htmnlData += `<tr>
                                                <td>${respData.temple_name}</td>
                                                <td>${respData.mobile}</td>
                                                <td>`;
                                if (respData.hour != null) {
                                    htmnlData += respData.hour.toFixed(2);
                                } else {
                                    htmnlData += 0;
                                }
                                htmnlData += `</td>
                                                <td><a href="{{ route('getattdetailedreport') }}?temple_id=${respData.temple_id}&month=${monthNo}&year=${yearNo}" target="_blank">List View</a></td>
                                            </tr>`;
                            }
                            $('.button-div').html(
                                `<button class="btn btn-sm btn-warning btn-wave btnSearchAtt waves-effect waves-dark" type="button">Search</button>
                            <button class="btn btn-sm btn-dark waves-effect wave-light download-excel"><i class="fa fa-file-excel" aria-hidden="true"></i></button>`
                                );
                            $('.tablelist').html(htmnlData);
                        }
                    })
                }

            });

            $(document).on('click', '.download-excel', function(e) {
                e.preventDefault();
                let yearNo = $('#year').val();
                let monthNo = $('#month_name').val();
                let table = $("#salescrm-table");
                TableToExcel.convert(table[
                    0
                    ], {
                    name: `monthly-attendance-${monthNo}-${yearNo}.xlsx`,
                    sheet: {
                        name: 'Sheet 1'
                    }
                });
            });

        });
    </script>
@endsection

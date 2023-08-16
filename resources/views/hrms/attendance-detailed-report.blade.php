@section('page-title', 'HRMS-Employees')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Detailed Attendance Report !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">HRMS</a></li>
                            <li class="breadcrumb-item active">Detailed Att. Report</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" style="overflow: scroll">
                        <h4 class="header-title">Detailed Attendance List</h4>
                        <table id="salescrm-table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Date</th>
                                    <th>Total Hour</th>
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
    <script>
        $(document).ready(function() {

            loadEmployeeData();

            function loadEmployeeData() {
                let temple_id = "{{ $_GET['temple_id'] }}";
                let month_no = "{{ $_GET['month'] }}";
                let year_no = "{{ $_GET['year'] }}";
                $.ajax({
                    url: "{{ route('detailedatendancedata') }}",
                    type: "get",
                    data: {
                        temple_id: temple_id,
                        month_no: month_no,
                        year_no: year_no
                    },
                    success: function(attendatnceData) {
                        let htmlData = '';
                        for (let i = 0; i < attendatnceData.length; i++) {
                            const attendanceData = attendatnceData[i];
                            htmlData += `<tr>
                                            <td>${i+1}</td>
                                            <td>${attendanceData.att_date}</td>
                                            <td>${attendanceData.hour.toFixed(2)}</td>
                                        </tr>`;
                        }
                        $('.tablelist').html(htmlData);
                    }
                });
            }
        });
    </script>
@endsection

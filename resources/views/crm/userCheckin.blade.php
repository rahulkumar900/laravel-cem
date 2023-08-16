@section('page-title', 'User Check-In')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <button class="btn btn-primary " id="checkin" type="button">Check-In</button>
                            </div>

                            <div class="col-md-6 mb-4">
                                <button class="btn btn-primary " id="checkout" type="button"
                                    disabled="">Check-Out</button>
                            </div>
                            <div class="col-md-12 mb-4" id="message" style="display: none">
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <li style="list-style: none">
                                            <span class="glyphicon glyphicon-exclamation-sign"></span>
                                            <span id="status"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
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

            $('#checkin').click();
            const status = document.querySelector('#status');

            $('#checkin').on("click", function() {
                document.getElementById('checkin').disabled = true;
                $.ajax({
                    type: "get",
                    url: "{{ route('markcheckin') }}",
                    success: function(response) {
                        if (response.type == true) {
                            $('#message').html(response.message);
                            sessionStorage.setItem("checkin_status", true);
                            localStorage.setItem("timeRemain", 1800);
                        } else {
                            alert("failed try again");
                        }
                    }
                });
            });

            $('#checkout').on('click', function() {
                document.getElementById('checkout').disabled = true;
                $.ajax({
                    type: "POST",
                    url: '/api/checkOut',
                    data: {
                        temple_id: "{{ Auth::user()->temple_id }}",
                        latitude: latitude,
                        longitude: longitude
                    },
                    success: function(response) {
                        if (response.check_out_status == 'N') {
                            $('#message').show();
                            $('#message').fadeIn();
                            document.getElementById('status').innerHTML = response.message;
                            document.getElementById('checkout').disabled = false;
                            $('#message').fadeOut(4000);
                        } else if (response.check_out_status == 'Y')
                            location.reload();
                    }
                });
            });



            loadEmployeeData();

            function loadEmployeeData() {
                let temple_id = "{{ Auth::user()->temple_id }}";
                let month_no = "{{ date('m') }}";
                let year_no = "{{ date('Y') }}";
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
                        let workingHours=0;
                        for (let i = 0; i < attendatnceData.length; i++) {
                            const attendanceData = attendatnceData[i];
                            let totalhour = attendanceData.hour.toFixed(2);
                            workingHours = parseFloat(totalhour) + workingHours;
                            htmlData += `<tr>
                                            <td>${i+1}</td>
                                            <td>${attendanceData.att_date}</td>
                                            <td>${totalhour}</td>
                                        </tr>`;
                        }
                        htmlData += `<tr>
                                        <th colspan="2">Total Hours</th>
                                        <th>${workingHours}</th>
                            </tr>`;
                        $('.tablelist').html(htmlData);
                    }
                });
            }

        });
    </script>
@endsection

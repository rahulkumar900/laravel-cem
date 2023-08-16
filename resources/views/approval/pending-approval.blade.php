@section('page-title', 'Leads Management')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css"
        integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Photo Profiles !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Approve Pending Profiles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive ">
                        <div class="col-12 d-flex justify-content-between">

                            <h4 class="header-title">Pending Profiles <span id="spinner_loading"
                                    style="display: none">Loading
                                    Wait <div class="spinner-grow text-success"></div></span></h4>
                            <h4 class="header-title">Today Aprove Profile:<span id="approve_by_me">0</span>
                            </h4>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse" id="approval_tble">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Asingn To</th>
                                        <th>Religion</th>
                                        <th>Caste</th>
                                        <th>Gender</th>
                                        <th>Income</th>
                                        <th>Profile Complete %</th>
                                        <th>Last Seen</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="load_lead_data">
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- Add Lead modal Starts --}}
    @include('form.modelProgressForm', ['data' => 'test', 'new' => 'name', 'title' => 'Approve Profile'])

    {{-- add photos modal ends --}}


    <style>
        #slider-div {
            display: flex;
            flex-direction: row;
            margin-top: 30px;
        }

        #slider-div>div {
            margin: 8px;
        }

        .slider-label {
            position: absolute;
            background-color: #eee;
            padding: 4px;
            font-size: 0.75rem;
        }

        .cropper-container {
            margin: 0 auto 20px auto;
        }
    </style>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
        integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        // load all users
        loadAllTemples();
        var cat_type = "photo"

        function loadAllTemples() {
            var temple_html = '<option value="">Select User</option>';
            var login_user = "{{ Auth::user()->temple_id }}";
            var temple_id_html = '';
            $.ajax({
                url: "{{ route('getalltemples') }}",
                type: "get",
                success: function(temple_response) {
                    for (let l = 0; l < temple_response.length; l++) {
                        const temple_list = temple_response[l];
                        if (temple_list.temple_id == login_user) {
                            temple_html +=
                                `<option selected="selected" value="${temple_list.temple_id}">${temple_list.name}</option>`;
                            temple_id_html +=
                                `<option selected="selected" value="${temple_list.id}">${temple_list.name}</option>`;
                        } else {
                            temple_html +=
                                `<option value="${temple_list.temple_id}">${temple_list.name}</option>`;
                            temple_id_html +=
                                `<option value="${temple_list.id}">${temple_list.name}</option>`;
                        }
                    }
                    $('#assign_to').html(temple_html);
                    $("#appoinemtn_with").html(temple_id_html);
                }
            });
        }

        $(document).ready(function() {
            $('.loader').hide();
            // upload image to server ends
            var some_id = $('#search_working_city');
            some_id.prop('type', 'text');
            some_id.removeAttr('autocomplete');

            /***** data loading and sending whatsapp message starts ******/
            $(document).on('click', '.btn_sendWhatsapp', function(e) {
                e.preventDefault();
                $('.whatsapp_div').html(
                    '<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Sending...</button>'
                );
                $.ajax({
                    type: "post",
                    url: "{{ route('sendmessageall') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'range': localStorage.getItem('range'),
                        'message_number': localStorage.getItem('message_number'),
                        'range': localStorage.getItem('range')
                    },
                    success: function(whatsapp_response) {
                        if (whatsapp_response.type == true) {
                            $('.whatsapp_div').html(
                                '<div class="alert alert-success" role="alert"><strong>' +
                                whatsapp_response.message + '</strong></div>');
                            countMessageNos("1-7");
                        } else {
                            $('.whatsapp_div').html(
                                '<div class="alert alert-danger" role="alert"><strong>' +
                                whatsapp_response.message + '</strong></div>');
                        }
                        window.setTimeout(function() {
                            $('.whatsapp_div').html(
                                '<button type="button" class="btn btn-warning btn_sendWhatsapp">Send Message</button>'
                            );
                        }, 2000);
                    }
                });
            });

            $(document).on('click', '.filter', function(e) {
                e.preventDefault();
                $('.filter').removeClass('btn-danger text-white');
                $('.filter').addClass('btn-outline-primary text-danger');
                $(this).removeClass('btn-outline-primary');
                $(this).addClass('btn-danger text-white');
                var date_range = $(this).attr('range');
                localStorage.setItem('range', date_range);
                table_data.ajax.reload();
                countMessageNos($(this).attr('range'));
            });

            $(document).on('click', '.message_number', function(e) {
                e.preventDefault();
                $('.message_number').removeClass('btn-danger text-white');
                $('.message_number').addClass('btn-outline-danger');
                $(this).removeClass('btn-outline-danger');
                $(this).addClass('btn-danger text-white');
                localStorage.setItem('message_number', $(this).attr('id'));
                table_data.ajax.reload();
            });

            // count message nos
            localStorage.setItem('range', '1-60');
            localStorage.setItem('message_number', '0');
            countMessageNos("1-60");

            function countMessageNos(dayRange) {
                $('.loader').show();
                $('.display_message_countings').html('<div class="spinner-grow text-danger text-center"></div>');
                msg_html = '';
                message_type = localStorage.getItem('message_number');
                $.ajax({
                    url: "{{ route('getmessagecounts') }}",
                    type: "get",
                    data: {
                        "day_range": dayRange,
                        'message_type': message_type
                    },
                    success: function(message_count_response) {
                        $('.loader').hide();
                        var span_text = ["Not Sent : ", "Message 0 : ", "Message 1 : ", "Message 2 : "];
                        var class_name = '';
                        msg_html += `<div class="col-md-12 row">`;
                        for (let i = 0; i < message_count_response.counting.length; i++) {
                            const texts = message_count_response.counting[i];
                            if (i == 0) {
                                class_name = 'btn-danger';
                            } else {
                                class_name = 'btn-outline-danger';
                            }
                            msg_html += `<div class="col-md-3">
                                        <button class="btn btn-sm ${class_name} message_number" id="${texts.messege_send_count}">Message :${texts.messege_send_count} ${texts.count}</button>
                            </div>`;
                        }
                        msg_html +=
                            `<div class="col-md-12">
                                    <button
                                        class="btn mb-2 btn-outline-success btn-block btn_sendWhatsapp">Send
                                        Message</button>
                                </div>`;
                        $('.display_message_countings').html(msg_html);
                    }
                });
            }


            toatalWork('photo')
            // load approval table
            var table_data = $('#approval_tble').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('dayrangewisedata') }}",
                    "type": "get",
                    "data": function(d) {
                        d.day_range = localStorage.getItem("range");
                        d.message_type = localStorage.getItem("message_number");
                        toatalWork("photo")
                    }
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: 'mobile',
                    },
                    {
                        data: 'assign_to',
                    },
                    {
                        data: 'religion',
                    },
                    {
                        data: 'caste',
                    },
                    {
                        data: 'gender',
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.annual_income > 10) {
                                return (parseFloat(data.annual_income) / 100000).toFixed(2) + "LPA";
                            } else {
                                return data.annual_income + "LPA";
                            }
                        }
                    }, {
                        data: function(data) {
                            if (data.stage > 1 && data.stage != null) {
                                return (data.stage - 1) * 20
                            } else {
                                return 20
                            }
                        },
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: function(data) {
                            return data.created_at.split('T')[0]
                        },
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData = `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.lead_id}"
                                    user_id="${data.user_id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">Check & Approve</span>
                                </button>
                                <p> https://hansmatrimony.com/fourReg/fullPage/edit/${data.lead_id}/1</p>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" id="${data.user_id}" userId="${data.user_id}">Reject</button>
                                <button type="button" class="btn btn-sm btn-danger markMarried" id="${data.user_id}" userId="${data.user_id}">Mark Married</button>
                                `;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });


            $(document).on('click', '.rejectProfile', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Reject")) {
                    $.ajax({
                        url: "{{ route('rejectuserprofile') }}",
                        type: "get",
                        data: {
                            "user_id": $(this).attr('userid')
                        },
                        success: function(rejectResponse) {
                            alert(rejectResponse.message);
                            table_data.ajax.reload();
                            toatalWork("{{ route('aprove-by-me') }}")
                        }
                    });
                }
            });
            $(document).on('click', '.markMarried', function(e) {
                e.preventDefault();
                if (confirm("Are You Sure To Mark Married")) {
                    $.ajax({
                        url: "{{ route('markMarrieduserprofile') }}",
                        type: "get",
                        data: {
                            "user_id": $(this).attr('userid')
                        },
                        success: function(rejectResponse) {
                            alert(rejectResponse.message);
                            table_data.ajax.reload();
                        }
                    });
                }
            });



            $(document).on('submit', '#addLeadForm', function(e) {
                e.preventDefault();
                $('.btn_div').text(`Please Wait...............`);
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(submitResponse) {
                        var messageHtml = ``;
                        if (submitResponse.type == true) {
                            messageHtml +=
                                `<div class="alert alert-success" role="alert"><strong>Message !</strong> ${submitResponse.message}</div>`;
                            $('#addLeadForm')[0].reset();
                            window.setTimeout(function() {
                                $('.form_output').html('');
                                table_data.ajax.reload();
                                $('#approveProfile').modal('hide');
                            }, 2000);
                        } else {
                            messageHtml +=
                                `<div class="alert alert-danger" role="alert"><strong>Message !</strong> ${submitResponse.message}</div>`;
                        }
                        $('.btn_div').html(
                            `<button type="submit" name="submit" class="btn btn-success">Approve</button>`
                        );
                        $('.form_output').html(messageHtml);
                    }
                });
            });

            function generateAbout(cityName) {
                var birthDateVal = $('#birth_date').val();

                var today = new Date();
                var birthDate = new Date(birthDateVal);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                var manglikStatus = $('#manglik_status').val();
                var mStatus = '';
                if (manglikStatus == 1) {
                    mStatus = 'Manglik';
                } else if (manglikStatus == 2) {
                    mStatus = 'Non-Manglik';
                } else if (manglikStatus == 3) {
                    mStatus = 'Anshik Manglik';
                } else if (manglikStatus == 4) {
                    mStatus = "Don't Know";
                }

                var gender = $('#lead_gender').val();
                var gName = '';
                if (gender == 1) {
                    gName = 'Man';
                } else {
                    gName = 'Woman';
                }

                getQualificationById($('#education_list').val());
                var qualification = localStorage.getItem('userDegree');

                var occupation = $('#occupation_list').val();
                var occupationString = '';
                if (occupation != 7 || occupation != 6) {
                    var occupationName = '';
                    if (occupation == 1) {
                        occupationName = "Business/Self Employed";
                    } else if (occupation == 2) {
                        occupationName = "Doctor";
                    } else if (occupation == 3) {
                        occupationName = "Government Job";
                    } else if (occupation == 4) {
                        occupationName = "Teacher";
                    } else if (occupation == 5) {
                        occupationName = "Private Job";
                    }
                    occupationString = `currently working as ${occupationName} in ${cityName}`;
                } else {
                    occupationString = '';
                }

                var stringAbout =
                    `I am ${mStatus} ${gName} residing in ${cityName}. I've completed my ${qualification} ${occupationString}.`;

                $('#about_me').val(stringAbout);
            }

            // get qualification by id
            function getQualificationById(qualification) {
                $.ajax({
                    url: "{{ route('getqualificationById') }}",
                    type: "get",
                    data: {
                        "qualification": qualification
                    },
                    success: function(qualresponse) {
                        localStorage.setItem('userDegree', qualresponse.degree_name);
                    }
                });
            }

        });
    </script>
@endsection

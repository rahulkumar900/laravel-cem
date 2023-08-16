@section('page-title', 'Request New Leads')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Request Exhaust Leads </h4>
                        <span class="message_title"></span>
                        {{-- exhaust leads --}}
                        <div class="row">
                            <div class="col-md-3">
                                <select id="income_range_exhaust" class="form-select">
                                    @foreach ($income_range as $annual_income)
                                        <option value="{{ $annual_income[0] }}">
                                            {{ $annual_income[1] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="lead_speed_exhaust" class="form-select">
                                    <option value="">Select Speed</option>
                                    <option value="Very High">Very High</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                    <option value="Less">Very Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="lead_credit" class="form-select">
                                    <option value="">Select Credit</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-3 text-center leadrequestdiv3">
                                <button class="btn btn-success btn-sm requestFbLead requestExhaustLead"
                                    dataType="3">Request Exhaust Leads</button>
                            </div>
                            <div class="col-md-12 displaydiv2"></div>
                        </div>
                    </div>
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>


    {{-- next follow up modal starts --}}

    <div class="modal fade" id="next_followup_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Next Followup Modal</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addleadsfollowpget') }}" method="post" id="lead_followup_form"
                        autocomplete="off">
                        @csrf
                        <input type="hidden" name="temple_id" value="{{ Auth::user()->temple_id }}">
                        <input type="text" class="d-none" id="followup_lead_id" name="followup_lead_id">
                        <input type="hidden" name="lead_type" value="3">
                        <input type="hidden" name="pick_lead_id" id="pick_lead_id">
                        <div class="form-group mb-2">
                            <label for="">Status of Followup</label>
                            <textarea class="form-control" name="followup_status" id="followup_status" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Next Followup Date</label>
                            <input type="date" value="{{ date('Y-m-d', strtotime('+4 days')) }}"
                                name="next_followup_date" id="next_followup_date" min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d', strtotime('+4 days')) }}" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Select Plan</label>
                            <select name="plan_id" id="plan_id" class="form-select">
                                <option value="">Select Plan</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Interest Level</label>
                            <select name="lead_speed" id="lead_speed" class="form-select">
                                <option value="">Interest Level</option>
                                <option value="Very High" selected>Very High</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                                <option value="Less">Less</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 followup_message">
                        </div>
                        <div class="form-group mb-2 float-end">
                            <button class="btn btn-success" type="submit" name="submit">Add Followup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- next follow up modal endss --}}

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.leadLimit').html(localStorage.getItem('lead_limit'));

            $(document).on('click', '.requestExhaustLead', function() {
                $('.leadrequestdiv3').html(
                    '<div class="spinner-border"></div>');
                var leadType = 3;
                var leadSpeed = $('#lead_speed_exhaust').val();
                var leadIncome = $('#income_range_exhaust').val();
                var credit = $("#lead_credit").val();

                loadLeadByType(leadType, leadSpeed, leadIncome, credit);
            });

            function loadLeadByType(leadType, leadSpeed, leadIncome, credit) {
                console.log(credit);
                $.ajax({
                    url: "{{ route('requestexhaustleadsdata') }}",
                    type: "get",
                    data: {
                        "lead_type": leadType,
                        "lead_speed": leadSpeed,
                        "lead_income": leadIncome,
                        "lead_credits": credit
                    },
                    success: function(lfbleadResp) {
                        $('.tab-pane').removeClass('active');
                        $('.nav-link').removeClass('active');
                        $('.link' + lfbleadResp.lead_type + '').addClass('active');
                        $('.div' + lfbleadResp.lead_type + '').addClass('active');
                        if (lfbleadResp.type == true) {
                            let leadtTypeString = "";
                            if (lfbleadResp.type == 0) {
                                leadtTypeString = "Website Lead";
                            } else if (lfbleadResp.type == 2) {
                                leadtTypeString = "Operator Calls";
                            } else if (lfbleadResp.type == 3) {
                                leadtTypeString = "Exhaust Leads";
                            } else if (lfbleadResp.type == 1) {
                                leadtTypeString = "Facebook Leads";
                            }
                            message_html = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>${leadtTypeString}!</strong> ${lfbleadResp.message}.
                                        </div>`;
                            $('.message_title').html(message_html);
                            requestedLeads();
                            window.setTimeout(() => {
                                $('.message_title').html('');
                            }, 2000);
                            var table_html = '';
                            table_html += `<table class="table table-striped table-inverse">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Name</th>
                                                    <th>Mobile</th>`;
                            // if (leadType == 0) {
                            //     table_html += `<th>Comments</th>`;
                            // }
                            if (leadType == 3) {
                                table_html += `<th>Credits</th>`;
                            }
                            table_html += `<th>Add to Lead</th>
                                                    <th>Not Pickup</th>
                                                </tr>
                                                </thead>
                                                <tbody>`;
                            localStorage.setItem('lead_count', lfbleadResp.data.length);
                            localStorage.setItem('lead_type', lfbleadResp.lead_type);
                            $('.leadrequestdiv3').html('');
                            for (let i = 0; i < lfbleadResp.data.length; i++) {
                                const leadsData = lfbleadResp.data[i];
                                table_html += `<tr class="fbrow${leadsData.id}">
                                                <td scope="row">${parseInt(i)+1}</td>
                                                <td>${leadsData.name}</td>
                                                <td>${leadsData.mobile}</td>`;
                                // if (leadType == 0) {
                                //     table_html +=
                                //         `<td><a class="btn btn-primary btn-sm" data-toggle="collapse" href="#contentId${i}" aria-expanded="false" aria-controls="contentId">Show</a><div class="collapse" id="contentId${i}">`;
                                //     if (lfbleadResp.data.comments != null) {
                                //         var splitData = leadsData.comments.split(";");
                                //         for (let u = 0; u < splitData.length; u++) {
                                //             const comment = splitData[u];
                                //             table_html += `<p>${comment}</p>`;
                                //         }
                                //     }
                                //     table_html += `</div></td>`;
                                // }
                                if (leadType == 3) {
                                    table_html += `<td>${leadsData.credit_available}</td>`;
                                }
                                table_html +=
                                    `<td><button class="btn btn-sm btn-success add_next_followup" id="${leadsData.id}" mobile="${leadsData.mobile}">Pickup</button></td>`;


                                table_html +=
                                    `<td><button class="btn btn-sm btn-danger btnNotpickFbLead" id="${leadsData.id}" leadName="${leadsData.name}" user_data_id="${leadsData.user_id}">Not Pickup</button></td></tr>`;
                            }
                            table_html += `</tbody></table>`;
                            $('.displaydiv2').html(table_html);
                        } else {
                            message_html = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>Message!</strong> ${lfbleadResp.message}.
                                        </div>`;
                            $('.message_title').html(message_html);
                            $('.leadrequestdiv3').html(
                                '<button class="btn btn-success btn-sm requestFbLead requestExhaustLead" dataType="3">Request Exhaust Leads</button>'
                            )
                        }
                    }
                });
            }

            loadLeadByTypeFirstCheck(3);

            function loadLeadByTypeFirstCheck(leadType) {
                $.ajax({
                    url: "{{ route('getlatrequestedleads') }}",
                    type: "get",
                    data: {
                        "lead_type": leadType
                    },
                    success: function(lfbleadRespNew) {
                        $('.tab-pane').removeClass('active');
                        $('.nav-link').removeClass('active');
                        $('.link' + lfbleadRespNew.lead_type + '').addClass('active');
                        $('.div' + lfbleadRespNew.lead_type + '').addClass('active');
                        if (lfbleadRespNew.type == true) {
                            let leadtTypeString = "";
                            let redirectUrl = "";
                            if (lfbleadRespNew.lead_type == 0) {
                                leadtTypeString = "Website Lead";
                                redirectUrl = "request-website-leads";
                            } else if (lfbleadRespNew.lead_type == 2) {
                                leadtTypeString = "Operator Calls";
                                redirectUrl = "request-operator-lcall-leads";
                            } else if (lfbleadRespNew.lead_type == 3) {
                                leadtTypeString = "Exhaust Leads";
                                redirectUrl = "request-exhaust-leads";
                            } else if (lfbleadRespNew.lead_type == 1) {
                                leadtTypeString = "Facebook Leads";
                                redirectUrl = "request-facebook-leads";
                            }
                            message_html = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                <span class="sr-only">Close</span>
                                            </button>
                                            <strong>${leadtTypeString}!</strong> ${lfbleadRespNew.message}.
                                        </div>`;
                            $('.message_title').html(message_html);
                            requestedLeads();
                            if (lfbleadRespNew.lead_type != 3) {
                                window.location.href = redirectUrl;
                            }

                            var table_html = '';
                            table_html += `<table class="table table-striped table-inverse">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Name</th>
                                                    <th>Mobile</th>`;
                            if (leadType == 0) {
                                table_html += `<th>Comments</th>`;
                            }
                            table_html += `<th>Add to Lead</th>
                                                    <th>Not Pickup</th>
                                                </tr>
                                                </thead>
                                                <tbody>`;
                            localStorage.setItem('lead_count', lfbleadRespNew.data.length);

                            for (let i = 0; i < lfbleadRespNew.data.length; i++) {
                                const leadsData = lfbleadRespNew.data[i];
                                table_html += `<tr class="fbrow${leadsData.id}">
                                                <td scope="row">${parseInt(i)+1}</td>
                                                <td>${leadsData.name}</td>
                                                <td>${leadsData.mobile}</td><td>`;
                                if (leadType == 0) {
                                    table_html += `<a class="btn btn-primary btn-sm" data-toggle="collapse" href="#contentId${i}" aria-expanded="false" aria-controls="contentId">Show
                                                        </a><div class="collapse" id="contentId${i}">`;
                                    if (leadsData.comments != null) {
                                        var splitData = leadsData.comments.split(";");
                                        for (let u = 0; u < splitData.length; u++) {
                                            const comment = splitData[u];
                                            table_html += `<p>${comment}</p>`;
                                        }
                                    }
                                    table_html += `</div>`;
                                }
                                table_html += `</td><td><button class="btn btn-sm btn-success add_next_followup" id="${leadsData.id}" mobile="${leadsData.mobile}" leadName="${leadsData.name}">Pickup</button></td>
                                                <td><button class="btn btn-sm btn-danger btnNotpickFbLead" id="${leadsData.id}" user_data_id="${leadsData.user_id}">Not Pickup</button></td>
                                            </tr>`;
                            }
                            table_html += `</tbody></table>`;
                            $('.displaydiv2').html(table_html);
                        } else {

                            $('.leadrequestdiv3').html(
                                '<button class="btn btn-success btn-sm requestExhaustLead" dataType="3">Request Exhaust Leads</button>'
                            );
                        }
                    }
                });
            }

            $(document).on('click', '.btnNotpickFbLead', function(e) {
                e.preventDefault();
                var leadId = $(this).attr('id');
                $('.btnNotpickFbLead').text('Please Wait');
                var userDataId = $(this).attr('user_data_id');
                $.ajax({
                    url: "{{ route('notpickwebleads') }}",
                    type: "get",
                    data: {
                        "lead_id": leadId,
                        "lead_type": 3,
                        'user_id': userDataId
                    },
                    success: function(notpickupResponse) {
                        if (notpickupResponse.type == true) {
                            $('.fbrow' + leadId + '').hide();
                            $('.btnNotpickFbLead').text('Not Pickup');
                            var prevRemainLead = parseInt(localStorage.getItem(
                                'lead_count'));
                            var remainLead = parseInt(prevRemainLead) - 1;

                            localStorage.setItem('lead_count', remainLead);
                            if (remainLead == 0) {
                                localStorage.setItem('lead_count', remainLead);
                                $('.leadrequestdiv3').html(
                                    '<button class="btn btn-success btn-sm requestFbLead requestExhaustLead" dataType="3">Request Exhaust Leads</button>'
                                );

                            }
                        } else {
                            $('.btnNotpickFbLead').text('Not Pickup');
                        }
                    }
                });
            });


            $(document).on('click', '.btnPickup', function(e) {
                e.preventDefault();
                $('.form_output').html("");
                var leadId = $(this).attr('id');
                const leadName = $(this).attr('leadName');
                //getLeadDetailsById(leadId);
                $('.fbrow' + leadId + '').hide();
                $('#pick_lead_id').val(leadId);

                var prevRemainLead = parseInt(localStorage.getItem('lead_count'));
                var remainLead = parseInt(prevRemainLead) - 1;

                localStorage.setItem('lead_count', remainLead);
                if (remainLead == 0) {
                    localStorage.setItem('lead_count', remainLead);
                    $('.leadrequestdiv1').html(
                        '<button class="btn btn-success btn-sm requestFbLead" dataType="1">Request FacebookLeads</button>'
                    );

                    $('.leadrequestdiv0').html(
                        '<button class="btn btn-success btn-sm requestFbLead" dataType="0">Request Website Leads</button>'
                    );

                    $('.leadrequestdiv3').html(
                        '<button class="btn btn-success btn-sm requestFbLead3" dataType="3">Request Exhaust Leads</button>'
                    );

                }
                $('#new_lead_mobile').val(parseInt($(this).attr('mobile')));
                $('#full_name').val(leadName);
                $('#security_key').val(localStorage.getItem('security_key'));
                $('#add_lead_modal').modal('show');
                $('.source_link').trigger('click');
            });


            function getLeadDetailsById(leadId) {
                $.ajax({
                    url: "{{ route('getleadeatailsbyid') }}",
                    type: "get",
                    data: {
                        lead_id: leadId
                    },
                    success: function(searchResp) {
                        if (searchResp != null) {
                            $('#profile_creating_for').val(searchResp.relationCode);
                            $('#lead_gender').val(searchResp.genderCode_user);
                            $('#full_name').val(searchResp.name);
                            $('#religion').val(searchResp.religionCode);
                            $('#castes').val(searchResp.casteCode_user);
                            $('#birth_date').val(searchResp.birth_date);
                            $('#marital_status').val(searchResp.maritalStatusCode);
                            $('#user_height').val(searchResp.height_int);
                            $('#weight').val(searchResp.weight);
                            $('#education_list').val(searchResp.educationCode_user);
                            $('#occupation_list').val(searchResp.occupationCode_user);
                            $('#occupation_list').val(searchResp.occupationCode_user);
                            $('#yearly_income').val(searchResp.monthly_income);
                        }
                    }
                });
            }

            // get religion
            getReligion();

            function getReligion() {
                religion_html = `<option value="">Select Religion</option>`;
                $.ajax({
                    url: "{{ route('allreligion') }}",
                    type: "get",
                    success: function(religions) {
                        for (let i = 0; i < religions.length; i++) {
                            const religion = religions[i];
                            religion_html +=
                                `<option value="${religion.religion}">${religion.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                    }
                })
            }

            // select gender automatically
            $(document).on('change', '#profile_creating_for', function() {
                if ($(this).val() == "4,Sister" || $(this).val() == "2,Mother" || $(this).val() == "7,Daughter") {
                    $('#lead_gender').val(2);
                    $('#lead_gender').prop("disabled", true);
                } else if ($(this).val() == "3,Father" || $(this).val() == "5,Brother" || $(this).val() == "6,Son") {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", true);
                } else {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", false);
                }
            });

            //add_next_followup
            $(document).on('click', '.add_next_followup', function(e) {
                e.preventDefault();
                var leadId = $(this).attr('id');
                $('#pick_lead_id').val(leadId);
                $('#followup_lead_id').val(leadId);
                $('.followup_message').html('');
                $('#next_followup_modal').modal('show');
                $('.fbrow' + leadId + '').hide();
            });



            // requested leads
            requestedLeads();

            function requestedLeads() {
                $.ajax({
                    url: "{{ route('countrequestedleads') }}",
                    type: "get",
                    success: function(requestedResponse) {
                        $('.leadRequested').html(requestedResponse.length);
                    }
                });
            }

            // set user categort and other data to localStorage
            loadUserCategoryData();

            function loadUserCategoryData() {
                $.ajax({
                    url: "{{ route('usercategoryrelations') }}",
                    type: "get",
                    success: function(detailsResponse) {
                        localStorage.setItem("call_count", detailsResponse.call_count);
                        localStorage.setItem("category", detailsResponse.category);
                        localStorage.setItem("category_id", detailsResponse.category_id);
                        localStorage.setItem("creation_date_time", detailsResponse.creation_date_time);
                        localStorage.setItem("lead_limit", detailsResponse.lead_limit);
                        localStorage.setItem("relation_name", detailsResponse.relation_name);
                        localStorage.setItem("temple_id", detailsResponse.temple_id);
                        localStorage.setItem("threshold_value", detailsResponse.threshold_value);
                    }
                });
            }

            // populate website leads
            $(document).on('click', '.populateWebsiteLead', function(e) {
                e.preventDefault();
                var websiteLeadTable = '';
                $(this).text("Please Wait");
                $(this).attr("disabled", true);
                var leadType = localStorage.getItem("");
                $.ajax({
                    url: "{{ route('getwebsiteleads') }}",
                    type: "get",
                    success: function(websiteLeadResponse) {
                        if (websiteLeadResponse.type == true) {
                            $('.populateWebsiteLead').text("Website Lead List");
                            websiteLeadTable += `<table class="table table-striped table-inverse">
                                            <thead class="thead-inverse">
                                                <tr>
                                                    <th>Sl.</th>
                                                    <th>Name</th>
                                                    <th>Mobile</th>`;
                            if (leadType == 0) {
                                websiteLeadTable += `<th>Comments</th>`;
                            }
                            websiteLeadTable += `<th>Add to Lead</th>
                                                    <th>Not Pickup</th>
                                                </tr>
                                                </thead>
                                                <tbody>`;
                            $('.populateWebsiteLead').attr("disabled", false);
                            for (let k = 0; k < websiteLeadResponse.data.length; k++) {
                                const webLeads = websiteLeadResponse.data[k];
                                websiteLeadTable += `<tr class="fbrow${webLeads.id}">
                                                <td scope="row">${parseInt(k)+1}</td>
                                                <td>${webLeads.name}</td>
                                                <td>${webLeads.mobile}</td>
                                                <td>    <a class="btn btn-primary" data-toggle="collapse" href="#contentId${k}" aria-expanded="false" aria-controls="contentId">Show
                                                        </a>
                                                    </p>
                                                    <div class="collapse" id="contentId${k}">`;
                                var splittedData = webLeads.comments.split(";");
                                for (let t = 0; t < splittedData.length; t++) {
                                    const comment = splittedData[t];
                                    websiteLeadTable += comment;
                                }
                                websiteLeadTable += `</div></td></tr>`;
                                /*
                                    <td><button class="btn btn-sm btn-success btnPickup" id="${webLeads.id}" mobile="${webLeads.mobile}">Pickup</button></td>
                                    <td><button class="btn btn-sm btn-danger btnNotpickFbLead" id="${webLeads.id}">Not Pickup</button></td>
                                */
                            }
                            $('.requestFbLead').show();
                        } else {
                            $(this).text("Website Lead List");
                            $('.populateWebsiteLead').attr("disabled", false);
                            websiteLeadTable = `<div class="alert alert-primary alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <strong>Alert!</strong> Something Went Wrong Try Again.
                                </div>`;
                        }
                        $('.displaydiv2').html(websiteLeadTable);
                        $(this).attr("disabled", false);
                    }
                });
            });


            // saving lead form
            $(document).on('submit', '#lead_followup_form', function(e) {
                e.preventDefault();
                var followup_html = '';
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(data_followup) {
                        if (data_followup.type == true) {
                            $('#lead_followup_form')[0].reset();
                            followup_html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Success!</strong> Followup Has Been Saved
                            </div>`;
                        } else {
                            followup_html = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>Alert!</strong> Failed to Save Record
                            </div>`;
                        }
                        $('.followup_message').html(followup_html);
                    }
                });
            });

            loadcountries();

            function loadcountries() {
                var countryHtml = '';
                $.ajax({
                    url: "{{ route('getallcountries') }}",
                    type: "get",
                    success: function(countryResp) {
                        for (let k = 0; k < countryResp.length; k++) {
                            const countryData = countryResp[k];
                            countryHtml +=
                                `<option value="${countryData.phonecode}">${countryData.sortname}</option>`;
                        }
                        //$('#country_code').html(countryHtml);
                        $('#country_code_al1').html(countryHtml);
                        $('#country_code_al2').html(countryHtml);
                        $('#country_code_al1').val(91);
                        $('#country_code_al2').val(91);
                        //$('#country_code').val(91);
                    }
                });
            }

            // get crm lead plans
            getCrmPlans();

            function getCrmPlans() {
                plan_optins = `<option value="">Select Plan</option>`;
                $.ajax({
                    url: "{{ route('crmleadplans') }}",
                    type: "get",
                    success: function(plan_resp) {
                        for (let i = 0; i < plan_resp.length; i++) {
                            const plan_amounts = plan_resp[i];
                            var plan_name = plan_amounts.type.split("_");
                            plan_optins +=
                                `<option value="${plan_name[0]}">${plan_name[0]}</option>`;
                        }
                        $('#plan_id').html(plan_optins);
                    }
                });
            }
        });
    </script>
@endsection
<p>

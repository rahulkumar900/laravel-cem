@section('page-title', 'Double Approval')
@extends('layouts.main-landingpage')
@section('page-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.css"
        integrity="sha512-6QxSiaKfNSQmmqwqpTNyhHErr+Bbm8u8HHSiinMEz0uimy9nu7lc/2NaXJiUJj2y4BApd5vgDjSHyLzC8nP6Ng=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Double Approval !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Approved Profiles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse" id="approval_tble">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Income</th>
                                        <th>Lead Value</th>
                                        <th>Mobile</th>
                                        <th>Marital Status</th>
                                        <th>Profile Complete %</th>
                                        <th>Last Seen</th>
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
    <script>
        $(document).ready(function() {

            // cropper js started

            let cropper;
            let cropperModalId = '#cropperModal';
            let $jsPhotoUploadInput = $('.js-photo-upload');

            $(document).on('change', '.js-photo-upload', function() {
                var files = this.files;
                console.log($(this).attr('userId'));
                $('#user_id_photo').val($(this).attr('userId'));
                if (files.length > 0) {
                    var photo = files[0];
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var image = $('.js-avatar-preview')[0];
                        image.src = event.target.result;

                        cropper = new Cropper(image, {
                            viewMode: 1,
                            aspectRatio: 3 / 4,
                            minContainerWidth: 400,
                            minContainerHeight: 400,
                            minCropBoxWidth: 271,
                            minCropBoxHeight: 271,
                            movable: true,
                            ready: function() {
                                // console.log('ready');
                                // console.log(cropper.ready);
                            }
                        });

                        $(cropperModalId).modal('show');
                    };
                    reader.readAsDataURL(photo);
                }
            });

            $('.js-save-cropped-avatar').on('click', function(event) {
                event.preventDefault();

                console.log(cropper.ready);

                var $button = $(this);
                //$button.text('uploading...');
                //$button.prop('disabled', true);

                const canvas = cropper.getCroppedCanvas();
                const base64encodedImage = canvas.toDataURL();
                $('#user_imagesixfour').val(base64encodedImage);
                $('#avatar-crop').attr('src', base64encodedImage);
                $.ajax({
                    url: "{{ route('uploaduserimage') }}",
                    type: "post",
                    data: {
                        "user_id": $('#user_id_photo').val(),
                        "user_image": base64encodedImage,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(updateImageResponse) {
                        $('.js-save-cropped-avatar').text('Save and Upload');
                        $('.js-save-cropped-avatar').prop('disabled', false);
                        window.setTimeout(function() {
                            cropper.destroy();
                            $(cropperModalId).modal('hide');
                            window.location.reload();
                        }, 300);
                    }
                })

            });
            // cropper js ends

            // upload image to server ends

            $('#search_lead_modal').modal('show');

            /************* data loading and sending whatsapp message ends *************/

            // select gender automatically
            $(document).on('change', '#profile_creating_for', function() {
                if ($(this).val() == 4 || $(this).val() == 7) {
                    $('#lead_gender').val(2);
                    $('#lead_gender').prop("disabled", true);
                } else if ($(this).val() == 5 || $(this).val() == 6) {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", true);
                } else {
                    $('#lead_gender').val(1);
                    $('#lead_gender').prop("disabled", false);
                }
            });

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
                        $('#religion_pref').html(religion_html);
                    }
                })
            }


            // load user passbook
            var table_data = $('#approval_tble').DataTable({
                "order": [
                    [1, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('getdoubleapproveprofile') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'name',
                    },
                    {
                        data: 'gender',
                    },
                    {
                        data: 'annual_income',
                    },
                    {
                        data: 'lead_value',
                    },
                    {
                        data: 'mobile',
                    },
                    {
                        data: 'marital_status',
                    },
                    {
                        data: 'profile_percent',
                    },
                    {
                        data: 'last_seen',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var btnData = `<button
                                    data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"
                                    lead_id="${data.lead_id}"
                                    user_id="${data.user_id}"
                                    class="btn btn-primary btn-sm checkNUpdate">
                                    <span style="color: white;">Update</span>
                                </button>
                                <p> https://hansmatrimony.com/fourReg/fullPage/edit/${data.user_id}/0</p>
                                <button type="button" class="btn btn-sm btn-danger rejectProfile" userId="${data.user_id}">Reject</button>
                                  <input type="file" name="photo" accept="image/*" class="js-photo-upload" userId="${data.user_id}">
                                `;
                            return btnData;
                        },
                        bsortable: false,
                    }
                ]
            });

            /*
             <button type="button" class="btn btn-sm btn-warning addPhotos" userId="${data.lead_id}">Add Photos</button>
            */
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
                        }
                    });
                }
            });

            $(document).on('click', '.checkNUpdate', function(e) {
                e.preventDefault();
                $('#user_data_id').val($(this).attr('user_id'));
                $.ajax({
                    type: "get",
                    url: "{{ route('getuserdatabyid') }}",
                    data: {
                        "user_id": $(this).attr('user_id'),
                        "lead_id": $(this).attr('lead_id')
                    },
                    success: function(userResponse) {
                        $('#approveProfile').modal('show');
                        $("#profile_creating_for").val(userResponse.relationCode);
                        $("#lead_gender").val(userResponse.genderCode_user);
                        $("#full_name").val(userResponse.name);
                        $("#religion").val(userResponse.religionCode);
                        $("#castes").val(userResponse.casteCode_user);
                        var nBdate = userResponse.birth_date.split(" ");
                        $("#birth_date").val(nBdate[0]);
                        $("#marital_status").val(userResponse.maritalStatusCode);
                        $("#user_height").val(userResponse.height_int);
                        $("#weight").val(userResponse.weight);

                        if (userResponse.educationCode_user != null) {
                            $("#education_list").val(userResponse.educationCode_user);
                        }

                        if (userResponse.occupationCode_user != null) {
                            $("#occupation_list").val(userResponse.occupationCode_user);
                        }

                        if (userResponse.working_city != null) {
                            $("#search_working_city").val(userResponse.working_city);
                        }

                        if (userResponse.height_int != null) {
                            $("#user_height").val(userResponse.height_int);
                        }

                        if (userResponse.weight != null) {
                            $("#weight").val(userResponse.weight);
                        }

                        if (userResponse.manglikCode != null) {
                            $("#manglik_status").val(userResponse.manglikCode);
                        }

                        if (userResponse.wishing_to_settle_abroad != null) {
                            $("#wish_to_go_abroad").val(userResponse.wishing_to_settle_abroad);
                        }

                        if (userResponse.monthly_income != null) {
                            $("#yearly_income").val(userResponse.monthly_income);
                        }
                        //  family details
                        if (userResponse.gotra != null) {
                            $("#family_gotra").val(userResponse.gotra);
                        }

                        if (userResponse.family_income != null) {
                            $("#family_income").val(userResponse.family_income);
                        }

                        if (userResponse.father_statusCode != null) {
                            $("#father_status").val(userResponse.father_statusCode);
                        }

                        if (userResponse.mother_statusCode != null) {
                            $("#mother_status").val(userResponse.mother_statusCode);
                        }
                        //"unmarried_sisters":0,"married_sisters":0,
                        // "unmarried_brothers":0,"married_brothers":0
                        if (userResponse.unmarried_brothers != null) {
                            $("#brothers").val(userResponse.unmarried_brothers);
                        }

                        if (userResponse.unmarried_sisters != null) {
                            $("#sisters").val(userResponse.unmarried_sisters);
                        }

                        if (userResponse.married_brothers != null) {
                            $("#married_brothers").val(userResponse.married_brothers);
                        }

                        if (userResponse.married_sisters != null) {
                            $("#married_sisters").val(userResponse.married_sisters);
                        }

                        if (userResponse.houseTypeCode != null) {
                            $("#house_type").val(userResponse.houseTypeCode);
                        }

                        if (userResponse.familyTypeCode != null) {
                            $("#family_type").val(userResponse.familyTypeCode);
                        }
                        var iamgeData = JSON.parse(userResponse.photo_url);
                        var photoHtml = '';
                        for (let f = 0; f < iamgeData.length; f++) {
                            const imageElement = iamgeData[f];
                            photoHtml += `<div class="mb-3 col-md-3 p-1 text-center">
                                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${imageElement}" class="w-100 rounded">
                                            </div>`;
                        }
                        $('.photo_viewer').html(photoHtml);
                    }
                });
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

            // load castes
            loadAllCastes();

            function loadAllCastes() {
                var caste_html = '<option value="">Select Caste</option>';
                $.ajax({
                    url: "{{ route('getallcastes') }}",
                    type: "get",
                    success: function(caste_Response) {
                        for (let k = 0; k < caste_Response.length; k++) {
                            const caste_list = caste_Response[k];
                            caste_html +=
                                `<option value="${caste_list.id}">${caste_list.caste ?? caste_list.value}</option>`;
                        }
                        $('#castes').html(caste_html);
                        $('#castes_pref').html(caste_html);
                    }
                });
            }

            // get parent occupation
            getParentOccupation();

            function getParentOccupation() {
                $.ajax({
                    url: "{{ route('getparentoccupation') }}",
                    type: "get",
                    success: function(parentOcResp) {
                        var prentHtml = '';
                        for (let m = 0; m < parentOcResp.length; m++) {
                            const pOccupations = parentOcResp[m];
                            prentHtml +=
                                `<option value="${pOccupations.id}">${pOccupations.name}</option>`;
                        }
                        $("#father_status").html(prentHtml);
                        $("#mother_status").html(prentHtml);
                    }
                });
            }

            // load all users
            loadAllTemples();

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

            // load religion
            loadReligion();

            function loadReligion() {
                var religion_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('allreligion') }}",
                    success: function(religion_resp) {
                        for (let q = 0; q < religion_resp.length; q++) {
                            const religsion_data = religion_resp[q];
                            religion_html +=
                                `<option value="${religsion_data.id}">${religsion_data.religion}</option>`;
                        }
                        $('#religion').html(religion_html);
                    }
                });
            }

            // load relation
            loadRelation();

            function loadRelation() {
                var relation_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getrelation') }}",
                    success: function(relation_resp) {
                        for (let p = 0; p < relation_resp.length; p++) {
                            const relation_data = relation_resp[p];
                            relation_html +=
                                `<option value="${relation_data.id}">${relation_data.name}</option>`;
                        }
                        $('#profile_creating_for').html(relation_html);
                    }
                });
            }

            // load marital status
            loadMaritalStatus();

            function loadMaritalStatus() {
                var marital_status_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getmaritalstatus') }}",
                    success: function(mstastus_resp) {
                        for (let o = 0; o < mstastus_resp.length; o++) {
                            const mstatus_data = mstastus_resp[o];
                            marital_status_html +=
                                `<option value="${mstatus_data.id}">${mstatus_data.name}</option>`;
                        }
                        $('#marital_status').html(marital_status_html);
                        $('#marital_status').val(1);
                    }
                });
            }

            // load manglik status
            loadManglikStatus();

            function loadManglikStatus() {
                var marital_status_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getmanglikstatus') }}",
                    success: function(mstastus_resp) {
                        for (let o = 0; o < mstastus_resp.length; o++) {
                            const mstatus_data = mstastus_resp[o];
                            marital_status_html +=
                                `<option value="${mstatus_data.id}">${mstatus_data.name}</option>`;
                        }
                        $('#manglik_status').html(marital_status_html);
                        $('#manglik_status').val(2);
                    }
                });
            }

            // load occupations
            loadOccupations();

            function loadOccupations() {
                var occupation_status_html = '';
                $.ajax({
                    type: "get",
                    url: "{{ route('getoccupation') }}",
                    success: function(occupation_resp) {
                        for (let n = 0; n < occupation_resp.length; n++) {
                            const occupation_data = occupation_resp[n];
                            occupation_status_html +=
                                `<option value="${occupation_data.id}">${occupation_data.name}</option>`;
                        }
                        $('#occupation_list').html(occupation_status_html);
                    }
                });
            }

            // polulate heights
            populateHeight();

            function populateHeight() {
                var height_values = '<option value="">Select Height</option>';
                for (let k = 48; k < 96; k++) {
                    height_values += `<option value="${k}">${Math.floor(k/12)} Ft ${k%12} In</option>`;
                }
                $('#user_height').html(height_values);
                $('#user_height').val(65);
            }


            $(document).on('keyup', '#search_working_city', function() {
                var cities_lsit = getCitiesName($(this).val());
            });

            function getCitiesName(city_name) {
                var city_html = ' <ul class="list-group city_list">';
                $.ajax({
                    url: "{{ route('getallcities') }}",
                    type: "get",
                    data: {
                        "city_name": city_name
                    },
                    success: function(city_response) {
                        for (let i = 0; i < city_response.length; i++) {
                            const city_names = city_response[i];
                            city_html +=
                                `<li class="list-group-item city_name" id="${city_names.id}" cityname="${city_names.city}, ${city_names.state}, ${city_names.country}">${city_names.city}, ${city_names.state}, ${city_names.country}</li>`;
                        }
                        city_html += '</ul>';
                        $('.cityListOptions').html(city_html);
                    }
                });
            }

            $(document).on('click', '.city_name', function() {
                var id = $(this).attr('id');
                var city_name = $(this).attr('cityname');
                $('.cityListOptions').html('');
                $('#working_city').val(id);
                $('#search_working_city').val(city_name);
                generateAbout(city_name);
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
                    `I am ${age} yrs old ${mStatus} ${gName} residing in ${cityName}. I've completed my ${qualification} ${occupationString}.`;

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

            // load qualifications
            loadQualifications();

            function loadQualifications() {
                var qual_html = '';
                $.ajax({
                    url: "{{ route('getalleducations') }}",
                    type: "get",
                    success: function(qualification_resp) {
                        for (let j = 0; j < qualification_resp.length; j++) {
                            const qualifications = qualification_resp[j];
                            qual_html +=
                                `<option value="${qualifications.id}" qualname="${qualifications.degree_name}">${qualifications.degree_name}</option>`;
                        }
                        $('#education_list').html(qual_html);
                    }
                });
            }
        });
    </script>
@endsection

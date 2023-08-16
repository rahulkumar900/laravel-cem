<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

<!-- CDN for multiselect jquery plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- CDN for CSS of chosen plugin -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" crossorigin="anonymous"
    referrerpolicy="no-referrer" />
{{-- including all javascript function of loading data using ajax --}}
<script src="{{ url('js/progressFormCustome.js') }}"></script>
<script>
    /* loading all data using ajax */
    loadAllCastes(multipule = false, id = ".caste", url =
        "{{ route('getallcastes') }}") //this function will load all caste for persnol stage
    loadReligion(multipule = false, id = ".religion", url =
        "{{ route('allreligion') }}") //this function will load all religion for persnol stage
    loadMaritalStatus("{{ route('getmaritalstatus') }}"); //this function will load all marital status value
    loadQualifications("{{ route('getalleducations') }}"); //this fanction load all quilification values
    loadQualifications2("{{ route('getalleducations') }}"); //this fanction load all quilification values
    populateHeight('.user_height'); //this function will load all height values
    getParentOccupation("{{ route('getparentoccupation') }}"); //this function will load all parents occupation values
    loadRelation("{{ route('getrelation') }}"); //this function will load all relation values
    loadManglikStatus("{{ route('getmanglikstatus') }}"); //this function will load all manglic status values
    loadOccupations("{{ route('getoccupation') }}"); //this function will load all occupations values
</script>
<style>
    .activestage1,
    .activestage2,
    .activestage3,
    .activestage4 {
        display: none;
    }

    .stage1,
    .stage2,
    .stage3,
    .stage4 {
        cursor: not-allowed
    }

    .multiselect-wrapper {
        width: 100%;
        height: 30px;

    }

    .multiselect-wrapper hr {
        margin: 0px
    }

    .multiselect-input {
        height: 40px;
    }

    .multiselect-input-div input,
    .multiselect-input-div input:focus-visible {
        border: 1px solid #1abc9c !important;
        outline: 1px solid #1abc9c !important;
    }

    .multiselect-list {
        width: 200px;
    }

    .multiselect-input-div input {
        cursor: text !important;
    }

    .city_list {
        cursor: pointer;
        position: absolute;
    }

    .chosen-choices {
        max-height: 50px !important;
        overflow-y: auto !important;
    }

    #religion_preference_chosen,
    #castes_pref_chosen {
        padding: 0px;
        width: 100% !important;
        border: 1px solid #1abc9c !important;
        outline: 1px solid #1abc9c !important;
    }
</style>
<script>
    const d = new Date();
    // this variable check about is edited by user or already data save in about if already data exist about will not set automaticaly
    var aboutSet = 0;
    // this variable check user stage how much stage data has been save 
    var stage = 1;
    /* this varibale use to check position off nav and stage*/
    var nav = 1
    /*  to check marital status and is_disable if true then disable repective disabilitydtail box and no of child box start */
    var marital_status = $('#maritalStatus').find(":selected").val();
    var is_disable = $('#is_disable').find(":selected").val();
    $('#maritalStatus').on('change', function() {
        marital_status = $(this).find(":selected").val();
        if (marital_status == '1') {
            $('#no_of_child').prop('disabled', true)
            $('#no_of_child').val('')
        } else {
            $('#no_of_child').prop('disabled', false)
        }
    })
    if (marital_status == 'Never Married') {
        $('#no_of_child').prop('disabled', true)
        $('#no_of_child').val('')
    }
    if (is_disable != '1') {
        $('#disability').prop('disabled', true)
        $('#disability').val('')
    }
    $('#is_disable').on('change', function() {
        is_disable = $(this).find(":selected").val();
        if (is_disable != '1') {
            $('#disability').prop('disabled', true)
            $('#disability').val('')
        } else {
            $('#disability').prop('disabled', false)
        }
    })
    /*  to check marital status and is_disable if true then disable repective disabilitydtail box and no of child box end */
    $('.navActive').click(function(e) {
        nav = $(this).data('id')
    })

    function toatalWork(cat = "profile") {
        $.ajax({
            url: "{{ route('aprove-by-me') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                cat: cat
            },
            type: "post",
            success: function(data) {
                $('#approve_by_me').text(data.total)
            }
        });
    }
    /* this function will submit data and change the stage to next tab */
    $('.submitenext').submit(function(e) {
        e.preventDefault()
        var newTab = $(this)
        var data = $(this).serialize();
        var url = $(this).attr("action")
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: "post",
            url: url,
            data: data,
            success: function(responseData, textStatus, jqXHR) {
                if (responseData >= 1) {
                    if (stage < responseData) {
                        stage = responseData
                    }
                    stageChange(stage);
                    $('#tabNav .nav-link').removeClass('active')
                    nav += 1
                    $('.nav' + nav).addClass('active')
                    newTab.next().addClass('active')
                    newTab.removeClass('active')
                    setAbout()
                }
            }
        })
    })
    /* submiting last stage */
    $('#profile-tab-5').submit(function(e) {
        e.preventDefault()
        var data = $(this).serialize();
        var url = $(this).attr("action")
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: "post",
            url: url,
            data: data,
            success: function(responseData, textStatus, jqXHR) {
                if (responseData != 0) {
                    closeModal();
                    if (typeof cat_type !== "undefined") {
                        toatalWork(cat_type);
                    }
                    location.reload();
                }
            }
        })
    })
    /*     this function will dicrise the value off nav and move the stage backword */
    $('.previous').click(function(e) {
        var new2 = $('form[class*="active"]')
        new2.prev().addClass('active')
        new2.removeClass('active')
        $('#tabNav .nav-link').removeClass('active')
        nav -= 1
        $('.nav' + nav).addClass('active')
    })
    /* this function activate stage after saving data */
    function stageChange(stageSet) {
        width = (stageSet - 1) * 20
        if (stageSet == 1) {
            width = 2
        }
        $('#bar .progress-bar').css('width', width +
            "%")
        if (width <= 20) {
            $('#bar .progress-bar').css('background-color', '#f1556c    ')
        } else if (width <= 40) {
            $('#bar .progress-bar').css('background-color', 'orange')
        } else if (width <= 60) {
            $('#bar .progress-bar').css('background-color', 'blue')
        } else {
            $('#bar .progress-bar').css('background-color', '#12d112')
        }
        for (let i = 1; i < 5; i++) {
            $(`.activestage${i}`).css('display', 'none')
            $(`.stage${i}`).css('display', 'block')
        }
        for (let i = 1; i < stageSet; i++) {
            $(`.activestage${i}`).css('display', 'block')
            $(`.stage${i}`).css('display', 'none')
        }
    }
</script>
{{-- script for feching data in  model form  --}}
<script>
    function closeModal() {
        $('#approveProfile').modal('hide');
    }

    $(document).on('click', '.checkNUpdate', function(e) {
        formDataLoad(e, $(this))
    });
    $(document).on('click', '.edit_n_approve_user', function(e) {
        $('#userDetailsModal').modal('hide')
        formDataLoad(e, $(this))
    });
    $(document).on('keyup', '.search_working_city', function() {
        if ($(this).val().length >= 3) {
            var cities_lsit = getCitiesName($(this));
        }
    });

    function isJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    function formDataLoad(e, thisId) {
        thisId.attr('disabled', false);
        id = thisId.attr('user_id')
        e.preventDefault();
        $('.loader').show();
        $('.user_data_id').val(thisId.attr('user_id'));
        $('#userPhoto').attr('data-id', thisId.attr('user_id'))
        $('#userPhoto').attr('userId', thisId.attr('user_id'))
        $('#print_user').attr('href', `{{ route('pdfprofiles') }}?user_ids=${id}`)
        nav = 1
        $.ajax({
            type: "get",
            url: "{{ route('getuserdatabyid') }}",
            data: {
                "user_id": thisId.attr('user_id'),
                "lead_id": thisId.attr('lead_id')
            },
            success: function(userResponse) {
                thisId.attr('disabled', false);
                $('.loader').hide();
                $('#tabNav .nav-link').removeClass('active')
                $('form').removeClass('active')
                $("#nav1").addClass('active')
                $("#profile-tab-1").addClass('active')
                $('#approveProfile').modal('show');
                if (userResponse.stage != null) {
                    stage = userResponse.stage;
                } else {
                    stage = 1
                }
                stageChange(stage)
                // setting data for persnol stage
                // setting relation value 
                var profile_creating_for = $(
                    `#profile_creating_for option[value="${userResponse.relationCode}"]`)
                if (profile_creating_for.length) {
                    $(`#profile_creating_for`).val(userResponse.relationCode)
                } else {
                    $(`#profile_creating_for`).val('1')
                }
                // set gender value 
                var lead_gender = $(`#lead_gender option[value="${userResponse.gender}"]`);
                if (lead_gender.length) {
                    $(`#lead_gender`).val(userResponse.gender)
                } else {
                    $(`#lead_gender`).val('Male')
                }
                // setting name value 
                $("#full_name").val(userResponse.name);
                // setting user food choice 
                var food_choice = $(`#food_choice option[value="${userResponse.food_choice}"]`)
                if (food_choice.length) {
                    $(`#food_choice`).val(userResponse.food_choice)
                } else {
                    $(`#food_choice`).val('Vegetarian')
                }
                // setting religion
                var religion_options = $(
                    `.religion option[value="${userResponse.mapping_id}"]`)
                if (religion_options.length) {
                    religion_options.attr('selected', true)
                } else {
                    $(`.religion`).val('1')
                }
                // setting caste value 
                var cast_options = $(
                    `.caste option[value="${userResponse.casteCode_user}@${userResponse.caste}"]`)
                if (cast_options.length) {
                    cast_options.attr('selected', true)
                } else {
                    $('.cast').val('1@96K Kokanastha')
                }
                // setting user birth_date 
                $("#birth_date").val(userResponse.birth_date != undefined ? userResponse
                    .birth_date
                    .split(" ")[0] : '01-01-1999');
                // setting user birth_time 
                if (userResponse.birth_time != undefined) {
                    var birth = userResponse.birth_time.split(':');
                    if (birth.length == 3) {
                        $("#birth_time").val(`${birth[0]}:${birth[1]}`);
                    } else {
                        $("#birth_time").val(userResponse.birth_time)
                    }

                } else {
                    $("#birth_time").val("12:00");
                }

                // setting user height 
                var user_height = $(`#user_height option[value="${userResponse.height_int}"]`)
                if (user_height.length) {
                    $('#user_height').val(userResponse.height_int)
                } else(
                    $('#user_height').val('')
                )
                // setting user weight
                $("#weight").val(userResponse.weight);
                /*  setting marital_status  when martial status is never married then input box off no off child will get disable else enable */
                var marital_status = $(
                    `#maritalStatus option[value="${userResponse.maritalStatusCode}"]`)
                if (marital_status.length) {
                    $('#maritalStatus').val(userResponse.maritalStatusCode)
                    if (userResponse.maritalStatusCode == '1') {
                        $('#no_of_child').prop('disabled', true)
                        $('#no_of_child').val('')
                    } else {
                        $('#no_of_child').prop('disabled', false)
                        if (userResponse.no_of_child != null) {
                            $('#no_of_child').val(userResponse.no_of_child)
                        } else {
                            $('#no_of_child').val('')
                        }
                    }
                } else {
                    $('#maritalStatus').val('')
                }
                /*  setting is_disable  when is_disable  is 0  then input box off no off disablity detail will get disable else enable */
                var is_disable = $(`#is_disable option[value="${userResponse.is_disable}"]`)
                if (is_disable.length) {
                    $(`#is_disable`).val(userResponse.is_disable)
                    if (userResponse.is_disable == '0') {
                        $('#disability').prop('disabled', true)
                        $('#disability').val('')
                    } else {
                        $('#disability').prop('disabled', false)
                        if (userResponse.disability != null) {
                            $('#disability').val(userResponse.disability)
                        } else {
                            $('#disability').val('')
                        }
                    }
                } else {
                    $(`#is_disable`).val('')
                }
                $("#birth_place").val(userResponse.birth_place);
                $("#alternate_number1").val(userResponse.mobile_family);
                $("#alternate_number2").val(userResponse.whatsapp_family);
                $("#whatsapp_no").val(userResponse.whatsapp);
                $("#email").val(userResponse.email_family);
                $("#locality").val(userResponse.locality);
                var manglik_option = $("#manglik_status option[value='" + userResponse.manglik + "']");
                if (manglik_option.length) {
                    $('#manglik_status').val(userResponse.manglik)
                } else {
                    $("#manglik_status").val('Non-Manglik');
                }
                // setting start for professional stage 
                var education_list_option = $(
                    `#education_list option[value="${userResponse.educationCode_user}"]`)
                if (education_list_option.length) {
                    $(`#education_list`).val(userResponse.educationCode_user)
                } else {
                    $(`#education_list`).val('1')
                }
                // setting college_ug value 
                if (userResponse.college_ug != null) {
                    $(`#college_ug`).val(userResponse.college_ug)
                } else {
                    $(`#college_ug`).val()
                }
                var education_ug_option = $(
                    `#education_ug option[value="${userResponse.education_ug}"]`)
                if (education_ug_option.length) {
                    $(`#education_ug`).val(userResponse.education_ug)
                } else {
                    $(`#education_ug`).val()
                }
                if (userResponse.college_pg != null) {
                    $(`#college_pg`).val(userResponse.college_pg)
                } else {
                    $(`#college_pg`).val()
                }
                if (userResponse.school_name != null) {
                    $(`#school_name`).val(userResponse.school_name)
                } else {
                    $(`#school_name`).val()
                }
                var education_pg_option = $(
                    `#education_pg option[value="${userResponse.education_pg}"]`)
                if (education_pg_option.length) {
                    $(`#education_pg`).val(userResponse.education_pg)
                } else {
                    $(`#education_pg`).val()
                }
                var occupation_list = $(
                    `#occupation_list option[value="${userResponse.occupationCode_user}"]`)
                if (occupation_list.length) {
                    $(`#occupation_list`).val(userResponse.occupationCode_user)
                } else {
                    $(`#occupation_list`).val('')
                }
                var wishing_to_settle_abroad = $(
                    `#wish_to_go_abroad option[value="${userResponse.wishing_to_settle_abroad}"]`)
                if (wishing_to_settle_abroad) {
                    $(`#wish_to_go_abroad`).val(userResponse.wishing_to_settle_abroad)
                } else {
                    $(`#wish_to_go_abroad`).val('')
                }
                if (userResponse.working_city != null) {
                    $("#search_working_city").val(userResponse.working_city);
                } else {
                    $("#search_working_city").val('');
                }
                var yearly_income = $(
                    `#yearly_income option[value="${userResponse.annual_income}"]`)
                if (yearly_income.length) {
                    $(`#yearly_income`).val(userResponse.annual_income)
                } else {
                    $(`#yearly_income`).val('')
                }
                if (userResponse.about != null && userResponse.about != '') {
                    $('#about_me').val(userResponse.about)
                    aboutSet = 1
                } else {
                    $('#about_me').val('')
                }
                $('#company_name').val(userResponse.company)
                $('#designation').val(userResponse.designation)
                $('#college_name').val(userResponse.college)
                $('#additional_degree').val(userResponse.additional_qualification)
                // setting start for family stage 
                //  family details
                if (userResponse.gotra != null && userResponse.gotra != 'null') {
                    $("#family_gotra").val(userResponse.gotra);
                } else {
                    $("#family_gotra").val('');
                }
                var family_income = $(`#family_income option[value="${userResponse.family_income}"]`)
                if (family_income.length) {
                    $(`#family_income`).val(userResponse.family_income)
                } else {
                    $(`#family_income`).val('')
                }
                var father_status = $(
                    `#father_status option[value="${userResponse.occupation_father_code}"]`)
                if (father_status.length) {
                    $(`#father_status`).val(userResponse.occupation_father_code)
                } else {
                    $(`#father_status`).val('8')
                }
                var mother_status = $(
                    `#mother_status option[value="${userResponse.occupation_mother_code}"]`)
                if (mother_status.length) {
                    $(`#mother_status`).val(userResponse.occupation_mother_code)
                } else {
                    $(`#mother_status`).val('8')
                }
                if (userResponse.unmarried_brothers != null) {
                    $("#brothers").val(userResponse.unmarried_brothers);
                } else {
                    $("#brothers").val('');
                }

                if (userResponse.unmarried_sisters != null) {
                    $("#sisters").val(userResponse.unmarried_sisters);
                } else {
                    $("#sisters").val('');
                }
                if (userResponse.married_brothers != null) {
                    $("#married_brothers").val(userResponse.married_brothers);
                } else {
                    $("#married_brothers").val('');
                }

                if (userResponse.married_sisters != null) {
                    $("#married_sisters").val(userResponse.married_sisters);
                } else {
                    $("#married_sisters").val('');
                }
                var houseTypeCode = $(`#house_type option[value="${userResponse.houseTypeCode}"]`)
                if (houseTypeCode.length) {
                    $(`#house_type`).val(userResponse.houseTypeCode)
                } else {
                    $(`#house_type`).val('')
                }
                var family_type = $(`#family_type option[value="${userResponse.familyTypeCode}"]`)
                if (family_type.length) {
                    $(`#family_type`).val(userResponse.familyTypeCode)
                } else {
                    $(`#family_type`).val('')
                }
                if (userResponse.city_family != null) {
                    $("#family_current_city").val(userResponse.city_family);
                } else {
                    $("#family_current_city").val('');
                }
                if (userResponse.native != null) {
                    $("#native_city").val(userResponse.native);
                } else {
                    $("#native_city").val('');
                }
                if (userResponse.contact_address != null) {
                    $("#contact_address").val(userResponse.contact_address);
                } else {
                    $("#contact_address").val('');
                }
                if (userResponse.about_family != null) {
                    $("#family_about").val(userResponse.about_family);
                } else {
                    $("#family_about").val('');
                }
                // setting value photo start 
                var imageData = userResponse.user_photos;
                var photoHtml = '';
                $('.photo_viewer').html('');
                for (let f = 0; f < imageData.length; f++) {
                    const imageElement = imageData[f];
                    if (imageElement.photo_status == "active" || imageElement
                        .photo_status == "pending") {
                        photoHtml += `<div class="mb-3 col-md-3 p-1 text-center imageDiv${userResponse.id}${f}" id="img${imageElement.id}"'>
                                <img src="https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${imageElement.photo_url}" class="w-100 rounded">
                                <button class="btn btn-sm btn-warning btnDelPic" userId="${userResponse.id}" indexPic="${imageElement.id}">Delete</button>
                            </div>`;
                    }
                }
                $('.photo_viewer').html(photoHtml);
                $('.rejectButton').attr("userId", thisId.attr('user_id'));
                // setting userpreference value 
                /* Setting Caste Prefrence Data */
                let caste_pref = userResponse.userpreference.castePref
                let caste_pref_str = userResponse.userpreference.caste
                if (caste_pref != null && caste_pref != undefined && caste_pref != 'null' &&
                    caste_pref_str != null && caste_pref_str != undefined && caste_pref_str != 'null') {
                    caste_pref = isJsonString(caste_pref) ? JSON.parse(caste_pref) : []
                    caste_pref_str = isJsonString(caste_pref_str) ? JSON.parse(caste_pref_str) : []
                    var caste_arr = []
                    if (caste_pref.length == caste_pref_str.length) {
                        for (i = 0; i < caste_pref_str.length; i++) {
                            caste_arr[i] = `${caste_pref[i]}@${caste_pref_str[i]}`
                        }
                    }
                    caste_data = caste_arr
                } else {
                    caste_data = []
                }

                loadAllCastes(multipule = true, id = "#castes_pref", url =
                    "{{ route('getallcastes') }}", caste_data)
                /* Setting Religion Prefrence Data */
                let religion_pref = userResponse.userpreference.religionPref
                // let religion_pref_str = userResponse.userpreference.religion
                if (religion_pref != null && religion_pref != undefined && religion_pref !=
                    'null') {
                    var religion = []
                    religion_pref = isJsonString(religion_pref) ? JSON.parse(religion_pref) : []
                    for (i = 0; i < religion_pref.length; i++) {
                        religion[i] = `${religion_pref[i]}`
                    }
                    religion_data = religion
                } else {
                    religion_data = []
                }
                loadReligion(multipule = true, id = "#religion_preference", url =
                    "{{ route('allreligion') }}", religion_data)
                $('#min_age').val(userResponse.userpreference.age_min ?? 21);
                $('#max_age').val(userResponse.userpreference.age_max ?? 35);
                var min_height = $(
                    `#min_height option[value="${userResponse.userpreference.height_min_s}"]`)
                if (min_height.length) {
                    $(`#min_height`).val(userResponse.userpreference.height_min_s)
                } else {
                    $(`#min_height`).val('')
                }
                var max_height = $(
                    `#max_height option[value="${userResponse.userpreference.height_max_s}"]`)
                if (max_height.length) {
                    $(`#max_height`).val(userResponse.userpreference.height_max_s)
                } else {
                    $(`#max_height`).val('')
                }
                var min_income = $(
                    `#min_income option[value="${userResponse.userpreference.income_min}"]`)
                if (min_income.length) {
                    $(`#min_income`).val(userResponse.userpreference.income_min)
                } else {
                    $(`#min_income`).val('0')
                }
                var max_income = $(
                    `#max_income option[value="${userResponse.userpreference.income_max}"]`)
                if (max_income.length) {
                    $(`#max_income`).val(userResponse.userpreference.income_max)
                } else {
                    $(`#max_income`).val('0')

                }
                var marital_status_pref = $(`#marital_status_perf option[value="${userResponse.userpreference
                    .marital_statusPref}"]`)
                if (marital_status_pref.length) {
                    $(`#marital_status_perf`).val(userResponse.userpreference
                        .marital_statusPref)
                } else {
                    $(`#marital_status_perf`).val('')
                }
                var manglik_pref = $(`#manglik_pref option[value="${userResponse.userpreference
                    .manglik}"]`)
                if (manglik_pref.length) {
                    $(`#manglik_pref`).val(userResponse.userpreference
                        .manglik)
                } else {
                    $(`#manglik_pref`).val('')
                }
                var food_choice = $(`#foodchoice_pref option[value="${userResponse.userpreference
                    .food_choicePref}"]`)
                if (food_choice.length) {
                    $(`#foodchoice_pref`).val(userResponse.userpreference
                        .food_choicePref)
                } else {
                    $(`#foodchoice_pref`).val('')
                }
                var occupation_status = $(`#occupation_status_perf option[value="${userResponse.userpreference
                    .workingPref}"]`)
                if (occupation_status.length) {
                    $(`#occupation_status_perf`).val(userResponse.userpreference
                        .workingPref)
                } else {
                    $(`#occupation_status_perf`).val('')
                }
                var citizenship_pref = $(`#citizenship_pref option[value="${userResponse.userpreference
                    .citizenship}"]`)
                if (citizenship_pref.length) {
                    $('#citizenship_pref').val(userResponse.userpreference
                        .citizenship)
                } else {
                    $('#citizenship_pref').val('')
                }

            }
        });
    }

    function getCitiesName(city_name) {
        var city_html = ' <ul class="list-group city_list">';
        $.ajax({
            url: "{{ route('getallcities') }}",
            type: "get",
            data: {
                "city_name": city_name.val()
            },
            success: function(city_response) {
                for (let i = 0; i < city_response.length; i++) {
                    const city_names = city_response[i];
                    city_html +=
                        `<li class="list-group-item city_name" data-id="${city_name.attr('id')}" >${city_names.city}, ${city_names.state}, ${city_names.country}</li>`;
                }
                city_html += '</ul>';
                city_name.next().html(city_html);
            }
        });
    }
    $(document).on('click', '.city_name', function() {
        var id = $(this).attr('id');
        var city_name = $(this).text();
        $('.cityListOptions').html('');
        $(`#${$(this).data('id')}`).val(city_name);
        setAbout()
    });
    $(document).on('change', '.changeAbout', function() {
        setAbout()
    });
    $(document).on('keyup', '.changeAboutKey', function() {
        setAbout()
    });
    $(document).on('keyup', '#about_me', function() {
        aboutSet = 1;
    });

    function setAbout() {
        var religion = $('#religion option:selected').text();
        var castes = $('#castes option:selected').text();
        var manglik_status = $('#manglik_status option:selected').text();
        var birth_place = $('#birth_place').val();
        var search_working_city = $('#search_working_city').val();
        var full_name = $('#full_name').val();
        var lead_gender = $('#lead_gender').val();
        var designation = $('#designation').val();
        var education_list = $('#education_list option:selected').text();
        var occupation_list = $('#occupation_list option:selected').text();
        var about = `Hello, I am ${full_name} `;
        about += `${religion}(${castes}) `
        if (manglik_status != "Don't Know" && manglik_status != "Doesn't Matter") {
            about += `${manglik_status}`;
        }
        if (lead_gender == "Male") {
            about += " man"
        } else {
            about += " women"
        }
        about += ` residing in ${birth_place}.I've completed my `
        about +=
            `${education_list} and ${occupation_list}${designation!=""?'('+designation+")":''}${search_working_city!=''?" in "+search_working_city:''}`
        if (aboutSet == 0) {
            $('#about_me').val(about)
        }
    }


    // for upload photo  using cropper js
    // cropper js started
    // this function use to distroy crop js when user close modal
    $('#cropperModal .close').on('click', function(e) {
        // do something...
        $('#cropperModal').modal('hide');
        cropper.destroy();
    })
    let cropper;
    let cropperModalId = '#cropperModal';
    let $jsPhotoUploadInput = $('.js-photo-upload');

    $(document).on('change', '.js-photo-upload', function() {
        var files = this.files;
        $('#user_id_photo').val($(this).attr('userId'));
        if (files.length > 0) {
            var photo = files[0];
            var reader = new FileReader();
            var image = $('.js-avatar-preview')[0];
            reader.onload = function(event) {
                image.src = event.target.result;
                $('.js-photo-upload').val('');
                cropper = new Cropper(image, {
                    viewMode: 1,
                    aspectRatio: 3 / 4,
                    minContainerWidth: 400,
                    minContainerHeight: 400,
                    minCropBoxWidth: 271,
                    minCropBoxHeight: 271,
                    movable: true,
                    ready: function() {

                    }
                });
                $(cropperModalId).modal({
                    backdrop: false,
                    keyboard: false
                });
                $(cropperModalId).modal('show');
            };
            reader.readAsDataURL(photo);
        }
    });

    $('.js-save-cropped-avatar').on('click', function(event) {
        event.preventDefault();
        // $('.loader').show();
        // var $button = $(this);
        // $button.text('uploading...');
        // $button.prop('disabled', true);
        const canvas = cropper.getCroppedCanvas();
        const base64encodedImage = canvas.toDataURL();
        $('#user_imagesixfour').val(base64encodedImage);
        $('#avatar-crop').attr('src', base64encodedImage);
        $.ajax({
            url: "{{ route('approvel-save-userProfileUpload') }}",
            type: "post",
            data: {
                "user_id": $('#user_id_photo').val(),
                "user_image": base64encodedImage,
                "_token": "{{ csrf_token() }}"
            },
            success: function(updateImageResponse) {
                $('.loader').hide();
                $('.js-save-cropped-avatar').text('Save and Upload');
                $('.js-save-cropped-avatar').prop('disabled', false);
                window.setTimeout(function() {
                    cropper.destroy();
                    $(cropperModalId).modal('hide');
                    $('.photo_viewer').append(`<div class="mb-3 col-md-3 p-1 text-center imageDiv${updateImageResponse.id}" id='img${updateImageResponse.id}'>
                                <img src="${updateImageResponse.path}" class="w-100 rounded">
                                <button class="btn btn-sm btn-warning btnDelPic" userId="${updateImageResponse.user_id}" indexPic="${updateImageResponse.id}">Delete</button>
                            </div>`)
                }, 300);
            }
        })

    });
    // delete profile pic
    $(document).on('click', '.btnDelPic', function(e) {
        e.preventDefault();
        if (confirm("Are You Sure To Delete?")) {
            var userId = $(this).attr('userId');
            var userPicIndex = $(this).attr('indexPic');
            var messageHtml = '';
            $.ajax({
                url: "{{ route('deleteuserpic') }}",
                type: "post",
                data: {
                    "user_id": userId,
                    "index_no": userPicIndex,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(delPicResp) {
                    $('.form_output').text('Please Wait.........');
                    if (delPicResp.type == true) {
                        $('.picDiv' + userId + userPicIndex + '').remove();
                        messageHtml +=
                            `<div class="alert alert-success" role="alert"><strong>Message !</strong> ${delPicResp.message}</div>`;
                        $(`#img${userPicIndex}`).fadeOut();
                    } else {
                        messageHtml +=
                            `<div class="alert alert-danger" role="alert"><strong>Message !</strong> ${delPicResp.message}</div>`;
                    }
                    $('.form_output').html(messageHtml);
                    window.setTimeout(function() {
                        $('.form_output').html('');
                    }, 2500);
                }
            });
        }
    });
    // cropper js ends
</script>

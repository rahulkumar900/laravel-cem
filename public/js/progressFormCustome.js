/*
 Loading data functions start :-In This section all fuction define from which aproval form data will load
*/
// load qualifications
/*
This function load all qualification data and set education select box options value
*/
function loadQualifications(url) {
    var qual_html = '';
    $.ajax({
        url: url,
        type: "get",
        success: function (qualification_resp) {
            for (let j = 0; j < qualification_resp.length; j++) {
                const qualifications = qualification_resp[j];
                qual_html +=
                    `<option value="${qualifications.id}" qualname="${qualifications.degree_name}">${qualifications.degree_name}</option>`;
            }
            $('#education_list').html(qual_html);
        }
    });
}
function loadQualifications2(url) {
    var qual_html = '';
    $.ajax({
        url: url,
        type: "get",
        success: function (qualification_resp) {
            qual_html +=
                `<option value="" >Select Digree</option>`;
            for (let j = 0; j < qualification_resp.length; j++) {
                const qualifications = qualification_resp[j];
                qual_html +=
                    `<option value="${qualifications.degree_name}" >${qualifications.degree_name}</option>`;
            }
            $('#education_ug').html(qual_html);
            $('#education_pg').html(qual_html);
        }
    });
}
// get parent occupation
/* 
This method fetch all parent occupation data and set mother and father occupation select options list value */
function getParentOccupation(url) {
    $.ajax({
        url: url,
        type: "get",
        success: function (parentOcResp) {
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
// load relation data
/* 
This function load all relation data and set profile creating for select options values */
function loadRelation(url) {
    var relation_html = '';
    $.ajax({
        type: "get",
        url: url,
        success: function (relation_resp) {
            for (let p = 0; p < relation_resp.length; p++) {
                const relation_data = relation_resp[p];
                relation_html +=
                    `<option value="${relation_data.id}">${relation_data.name}</option>`;
            }
            $('#profile_creating_for').html(relation_html);
        }
    });
}
// load marital status data
/* 
This Function will load all marital status data and set marital status select options value and marital status prefer values
*/
function loadMaritalStatus(url) {
    var marital_status_html = '';
    $.ajax({
        type: "get",
        url: url,
        success: function (mstastus_resp) {
            for (let o = 0; o < mstastus_resp.length; o++) {
                const mstatus_data = mstastus_resp[o];
                if (mstatus_data.name != "Married") {
                    marital_status_html +=
                        `<option value="${mstatus_data.id}">${mstatus_data.name}</option>`;
                }
            }
            $('#maritalStatus').html(marital_status_html);
            $('#maritalStatus').val(1);
            $('#marital_status_perf').html(marital_status_html);
        }
    });
}
// load manglik status
/* This Function Will load all mangalik status data and set manglik status and manglic status pref options value */
function loadManglikStatus(url) {
    var marital_status_html = '';
    $.ajax({
        type: "get",
        url: url,
        success: function (mstastus_resp) {
            for (let o = 0; o < mstastus_resp.length; o++) {
                const mstatus_data = mstastus_resp[o];
                marital_status_html +=
                    `<option value="${mstatus_data.name}">${mstatus_data.name}</option>`;
            }
            $('#manglik_status').html(marital_status_html);
            $('#manglik_status').val(2);
            $('#manglik_pref').html(marital_status_html);
        }
    });
}
// load religion data 
/* this function will load all will load all religion data and set relegion options values it will take one value as parameter  this will define by defaul selected data.data variable contain all saved data of user's religion pref in database and this function also set religion pref data with multiselect option using chose library  */
function loadReligion(multipule = true, id = "#religion_preference", url, data = []) {
    var religion_html = '';
    var data = data

    $.ajax({
        type: "get",
        url: url,
        success: function (religion_resp) {
            for (let q = 0; q < religion_resp.length; q++) {
                const religsion_data = religion_resp[q];
                religion_html +=
                    `<option value="${religsion_data.mapping_id}" >${religsion_data.religion}</option>`;

            }
            if (multipule) {
                $(id).html(religion_html);
                setMultiselect(data, id)
            } else {
                $(id).html(religion_html);
            }
        }
    });
}
// loading cast data 
/* this function will load all will load all Caste data and set Caste options values it will take one value as parameter  this will define by defaul selected data.data variable contain all saved data of user's Caste pref in database this function also set caste pref with multi select option using chose library  */
function loadAllCastes(multipule = true, id = "#castes_pref", url, data = []) {
    var data = data
    var caste_html = '';
    $.ajax({
        url: url,
        type: "get",
        success: function (caste_Response) {
            for (let k = 0; k < caste_Response.length; k++) {
                const caste_list = caste_Response[k];
                if (caste_list.id != 0) {
                    caste_html +=
                        `<option value="${caste_list.id}@${caste_list.caste ?? caste_list.value}">${caste_list.caste ?? caste_list.value}</option>`;
                }
            }
            if (multipule) {
                $(`${id}`).html(caste_html);
                setMultiselect(data, `${id}`)

            } else {
                $(`${id}`).html(caste_html);
            }
        }
    });
}
/* this function use to mak multiselect option using choosen library */
function setMultiselect(data, id) {
    if (data.length != 0) {
        for (i in data) {
            $(`${id} option[value="${data[i]}"]`).attr('selected', true)
        }
    }

    $(`${id}`).chosen('destroy');
    $(`${id}`).chosen({
        hide_results_on_select: false,
    });
    $(`${id}_chosen`).attr('placeholder', 'Select Religion')
    $(`${id}_chosen`).addClass('form-select')
    $(`${id}_input`).attr('required', true)
}
$(`#caste_lists`).chosen({
    hide_results_on_select: false,
})
// load occupations
/* This Function Will load and set all occupations data and  pref ocation data */
function loadOccupations(url) {
    var occupation_status_html = '';
    $.ajax({
        type: "get",
        url: url,
        success: function (occupation_resp) {
            for (let n = 0; n < occupation_resp.length; n++) {
                const occupation_data = occupation_resp[n];
                occupation_status_html +=
                    `<option value="${occupation_data.id}">${occupation_data.name}</option>`;
            }
            $('#occupation_list').html(occupation_status_html);
            $('#occupation_status_perf').html(occupation_status_html);
        }
    });
}

// polulate heights
/* This function will set Height Data It will take html elemnt id to set perticular select options data*/
function populateHeight(id) {
    var height_values = '<option value="">Select Height</option>';
    for (let k = 48; k < 96; k++) {
        height_values += `<option value="${k}">${Math.floor(k / 12)} Ft ${k % 12} In</option>`;
    }
    $(id).html(height_values);
    $(id).val(65);
}
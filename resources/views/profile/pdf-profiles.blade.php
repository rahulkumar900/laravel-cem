<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Profile PDf Creation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style rel="stylesheet" href="{{ url('libs/pdfmake/build/pdfmake.min.js') }}"></style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.0/css/font-awesome.min.css"
        integrity="sha512-FEQLazq9ecqLN5T6wWq26hCZf7kPqUbFC9vsHNbXMJtSZZWAcbJspT+/NEAQkBfFReZ8r9QlA9JHaAuo28MTJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        td,
        th {
            text-align: left
        }
    </style>
</head>

<body>
    <div class="container pdfDiv" id="pdf_data">
        <div class="row shadow" style="/* height: 99vh;  */ page-break-after: always;">
            <div class="col-md-12 text-center">
                <img src="{{ url('images/hans_logo.png') }}" class="main-banner-img mt-3 w-75" alt="">
                <h4 class="d-none">Twango Social Network Pvt. Ltd.</h4>
                <hr>
                <div class="row mt-5 shadow-sm text-center">
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5> Website: www.hansmatrimony.com</h5>
                    </div>
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5>Mail us at : info@hansmatrimony.com</h5>
                    </div>
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5>Add : H-18 Bali Nagar, New Delhi</h5>
                    </div>
                    <div class="col-md-6 mb-3 p-2 ">
                        <h5>Contact : +91 969 798 9697</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center mt-2">
                <img src="{{ url('images/1e1ef807efe66d39bc6b9402d0f1d62b_collage_450.jpg') }}" alt=""
                    class="img-thumbnail rounded" style="height: 650px; width: auto">
                <h5 class="mt-5 d-none">Dear Shubham Bhatia Please Find the Attached Profiles As Per Your Requirement
                </h5>
            </div>
            <div class="col-md-12 border-bottom border-top" style="height: 80px;">
                <div class="row pt-3">
                    <div class="col-sm-2 text-center">
                        <a href="https://www.facebook.com/HansMatrimony" class="btn"><i class="fa fa-2x fa-facebook"
                                aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://www.instagram.com/hansmatrimony/" class="btn"><i
                                class="fa fa-2x fa-instagram" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://www.linkedin.com/company/hansmatrimony/" class="btn"><i
                                class="fa fa-2x fa-linkedin" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://www.youtube.com/channel/UCXeEAxOuoMoBCcu45PEtfmg/featured" class="btn"><i
                                class="fa fa-2x fa-youtube" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://twitter.com/HansMatrimony" class="btn"><i class="fa fa-2x fa-twitter"
                                aria-hidden="true"></i></a>
                    </div>
                    <div class="col-sm-2 text-center">
                        <a href="https://g.page/HansMatrimony?share" class="btn"><i class="fa fa-2x fa-map-marker"
                                aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .pd-image img {
            object-fit: contain;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .complete-height {
            /* height: 99vh; */
            margin: 10px 0px 10px 0px;
            page-break-after: always;
        }

        .watermark {
            background: url("{{ url('images/logo-sm-dark.png') }}") center center no-repeat;
            opacity: 0.1;
            opacity: 0.1;
            position: absolute;
            width: 100%;
            height: 100%;
        }

        @media print {

            html,
            body {
                border: 1px solid white;
                height: 99%;
                page-break-after: avoid;
                page-break-before: avoid;
            }
        }
    </style>
    <script src="{{ url('js/vendor.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function getIncomeRange(income) {
                var income_range = 0;
                switch (true) {
                    case income === 0:
                        income_range = "No Income";
                        break;
                    case income >= 1.0 && income <= 2.5:
                        income_range = "1.0-2.5 Lakh/Year";
                        break;
                    case income > 2.5 && income <= 5.0:
                        income_range = "2.5-5.0 Lakh/Year";
                        break;
                    case income > 5.0 && income <= 7.5:
                        income_range = "5.0-7.5 Lakh/Year";
                        break;
                    case income > 7.5 && income <= 10.0:
                        income_range = "7.5-10.0 Lakh/Year";
                        break;
                    case income > 10.0 && income <= 15.0:
                        income_range = "10.0-15.0 Lakh/Year";
                        break;
                    case income > 15.0 && income <= 20.0:
                        income_range = "15.0-20.0 Lakh/Year";
                        break;
                    case income > 20.0 && income <= 25.0:
                        income_range = "20.0-25.0 Lakh/Year";
                        break;
                    case income > 25.0 && income <= 30.0:
                        income_range = "25.0-30.0 Lakh/Year";
                        break;
                    case income > 30.0 && income <= 50.0:
                        income_range = "30.0-50.0 Lakh/Year";
                        break;
                    case income > 50.0 && income <= 70.0:
                        income_range = "50.0-70.0 Lakh/Year";
                        break;
                    case income > 70.0 && income <= 100.0:
                        income_range = "70.0-100.0 Lakh/Year";
                        break;
                    case income > 100.0:
                        income_range = "1 cr+ /Year";
                        break;
                    default:
                        income_range = "No Income";
                }

                return income_range
            }
            loadProfiles();

            function tConv24(time24) {
                var ts = time24;
                var H = +ts.substr(0, 2);
                var h = (H % 12) || 12;
                h = (h < 10) ? ("0" + h) : h; // leading 0 at the left for 1 digit hours
                var ampm = H < 12 ? " AM" : " PM";
                ts = h + ts.substr(2, 3) + ampm;
                return ts;
            };

            function loadProfiles() {
                var htmlData = '';
                htmlData += '<div class="html21pdf_page-break"></div>';
                $.ajax({
                    url: "{{ route('showprofilesingroups') }}",
                    type: "get",
                    data: {
                        "user_ids": "{{ $_GET['user_ids'] }}"
                    },
                    success: function(profileResponse) {
                        // console.log(profileResponse[0]);
                        for (let i = 0; i < profileResponse.length; i++) {
                            htmlData += '<div class="html21pdf_page-break"></div>';
                            const userDetails = profileResponse[i];
                            var photoData = userDetails.user_photos;

                            var heightTotal = '';
                            var inchHeight = '';

                            if (userDetails.height) {
                                var heightFeet = Math.trunc((parseInt(userDetails.height) / 12));
                                var inchesTotal = parseInt(userDetails.height) % 12;
                                if (inchesTotal > 0) {
                                    inchHeight = inchesTotal + 'in';
                                } else {
                                    inchHeight = ' ';
                                }
                                heightTotal = heightFeet + 'ft ' + inchHeight;
                            } else {
                                heightTotal = "N.A.";
                            }

                            htmlData += `<div class="row complete-height shadow-sm">
                                            <div class="col-md-12">
                                                <div class="wrapper">
                                                    <div class="watermark"></div>
                                                    <div class="inner-container">
                                                        <div class="col-md-12 row text-center">
                                                        <div class="col-12 text-center m-auto"><h4>Photos</h4></div>
                                                        
                                                            `;
                            // console.log(photoData[].photo_url)
                            if (photoData.length > 0) {
                                for (j = 0; j < photoData.length; j++) {
                                    htmlData += `<div class = "col-sm-6 m-auto text-center pd-image" >
                                <img src =
                                "https://s3.ap-south-1.amazonaws.com/hansmatrimony/uploads/${photoData[j].photo_url}"
                                class = "img-thumbnail pb-2 pt-2" 
                                alt = "${photoData[j].photo_url}" >
                                </div> 
                            `
                                }
                                htmlData += `</div>`

                            } else {
                                htmlData += ` <div class = "col-md-6 text-center">
                                <h4> Photo Not Available </h4> </div>`;
                            }
                            var formattedDate = "";
                            var splitBDateMon = "";
                            var splitBDate = "";
                            if (userDetails.birth_date != null || userDetails.birth_date != undefined) {
                                splitBDate = userDetails.birth_date.split(" ");
                                splitBDateMon = splitBDate[0].split("-");
                                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                                    "Aug",
                                    "Sep", "Oct", "Nov", "Dec"
                                ];
                                formattedDate = splitBDateMon[2] + ' ' + monthNames[parseInt(
                                    splitBDateMon[1] - 1)] + ' ' + splitBDateMon[0];

                            } else {
                                formattedDate = "N.A.";
                            }



                            htmlData += `<div class="col-md-12 table-responsive mt-3">
                                                <table class="table table-striped table-inverse">
                                                    <tr>
                                                        <th colspan="4" class="text-center">Personal Details</th>
                                                    </tr>
                                                        <tr>
                                                            <th>Name</th>
                                                            <td>${userDetails.name!=null?userDetails.name:'N.A.'}</td>
                                                            <th>Gender</th>
                                                            <td colspan="3">${userDetails.gender!=null?userDetails.gender:'N.A.'}</td>
                                                        </tr>
                                                        <tr>
                                                                <th>Birth Date</th>
                                                                <td>${formattedDate!=null?formattedDate:'N.A.'}</td>
                                                                <th>Birth Time</th>
                                                                <td>${userDetails.birth_time!=null?tConv24(userDetails.birth_time):'N.A.'}</td>
                                                        </tr>
                                                        <tr>
                                                           
                                                            <th>Religion</th>
                                                            <td>${userDetails.religion!=null?userDetails.religion:'N.A.'}</td>
                                                            <th>Caste</th><td>`;
                            if (userDetails.caste == null || userDetails.caste == 'All') {
                                htmlData += "Others";
                            } else {
                                htmlData += userDetails.caste;
                            }
                            htmlData += `</td>
                                                            `;
                            htmlData += `</tr><tr><th>Height</th>
                                                            <td>${heightTotal}</td>
                                                            <th>Weight</th>
                                                            <td>${userDetails.weight!=null && userDetails.weight!='null'?userDetails.weight+"Kg":'N.A.'}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Marital Status</th>
                                                        <td>${userDetails.marital_status!=null?userDetails.marital_status:'N.A.'}</td>
                                                            <th>Manglik Status</th>
                                                        <td>${userDetails.manglik!=null?userDetails.manglik:'N.A.'}</td>
                                                            </tr>
                                                        <tr>
                                                            <th>Food Choice</th>
                                                        <td>${userDetails.food_choice!=null?userDetails.food_choice:'N.A.'}</td>
                                                            <th>Birth Place</th>
                                                        <td>${userDetails.birth_place!=null?userDetails.birth_place:'N.A.'}</td>
                                                            </tr>
                                                        `
                            if (userDetails.disability != null && userDetails.disability != undefined &&
                                userDetails.disability != 'No') {
                                htmlData += `<tr>  <th>Disability</th>
                                                        <td>Yes</td>
                                                            </tr>`;
                            }
                            htmlData += `</table>
                                                </div>
                                            </div>
                                            <div class="col-md-12 table-responsive mt-3">
                                                <table class="table table-striped table-inverse">
                                                    <tr>
                                                        <th colspan="4" class="text-center">Professional Details</th>
                                                    </tr>
                                                    <tr>
                                                            
                                                            <th>Highest Degree</th>
                                                            <td>${userDetails.education!=null?userDetails.education:'N.A.'}</td>
                                                            <th>Occupation</th>
                                                            <td>${userDetails.occupation!=null?userDetails.occupation:'N.A.'}</td>
                                                        </tr>
                                                        `
                            if (userDetails.college_ug != null && userDetails.education_ug != null) {
                                htmlData +=
                                    ` <tr>                                                            
                                                            <th>UG College</th>
                                                            <td>${userDetails.college_ug!=null?userDetails.college_ug:'N.A.'}</td>
                                                            <th>UG Degree</th>
                                                            <td>${userDetails.education_ug!=null?userDetails.education_ug:'N.A.'}</td>
                                                        </tr>`
                            }
                            if (userDetails.college_pg != null && userDetails.education_pg != null) {
                                htmlData +=
                                    `  <tr>                                                            
                                                            <th>PG College</th>
                                                            <td>${userDetails.college_pg!=null?userDetails.college_pg:'N.A.'}</td>
                                                            <th>PG Degree</th>
                                                            <td>${userDetails.education_pg!=null?userDetails.education_pg:'N.A.'}</td>
                                                        </tr>`
                            }

                            htmlData +=
                                `
                                                        <tr>
                                                          
                                                            <th>Working City</th>
                                                            <td>${userDetails.working_city!=null?userDetails.working_city:'N.A.'}</td>
                                                            <th>Income</th>
                                                            <td>`;
                            htmlData += getIncomeRange(userDetails.annual_income);
                            htmlData += `</td>
                                                        </tr>
                                                      
                                                        <tr>
                                                            <th >Wish To Go Abroad</th><td>`;
                            if (userDetails.wishing_to_settle_abroad == null || userDetails
                                .wishing_to_settle_abroad == 0) {
                                htmlData += 'No';
                            } else {
                                htmlData += 'Yes';
                            }
                            htmlData += `</td> <th>Resident Status</th> <td>`;
                            if (userDetails.working_city) {
                                if (userDetails.working_city.search("India") > 0 || userDetails
                                    .working_city == null || userDetails.working_city == undefined ||
                                    userDetails.working_city == 'NA' || userDetails.working_city ==
                                    'N.A.' || userDetails.working_city ==
                                    'na') {
                                    htmlData += ' Indian';
                                } else {
                                    htmlData += ' NRI';
                                }
                            } else {
                                htmlData += ' Indian';
                            }
                            htmlData += `</td>
                                                        </tr>
                                                        <tr>
                                                        <th>Company Name</th>
                                                        <td>${userDetails.company!=null?userDetails.company:'N.A.'}</td>
                                                        <th>Designation</th>
                                                        <td>${userDetails.designation!=null?userDetails.designation:'N.A.'}</td>
                                                        </tr>
                                                      
                                                           
                                                        <th>College Name</th>
                                                        <td>${userDetails.college!=null?userDetails.college:'N.A.'}</td>
                                                        <th>Additional Degree</th>
                                                        <td>${userDetails.additional_qualification!=null?userDetails.additional_qualification:'N.A.'}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>About</th>
                                                            <td rowspan="5" colspan="3">${userDetails.about || ''}  </td>
                                                        </tr>`;
                            if (userDetails.disability == 'yes') {
                                htmlData += `<tr>
                                                                        <th rowspan="5" colspan="4"><strong>Disablity</strong>${userDetails.disabled_part}  </th>
                                                                    </tr>`;
                            }

                            htmlData += `</table>
                                                </div>
                                            </div>
                                            <div class="col-md-12  table-responsive mt-4">
                                                <table class="table table-striped table-inverse">
                                                    <tr>
                                                        <th colspan="8" class="text-center">Family Details</th>
                                                    </tr>
                                                        <tr>
                                                            <th>Father Occupation</th><td>${userDetails.occupation_father || '--'}</td>
                                                            <th>Mother Occupation</th><td>${userDetails.occupation_mother || '--'}</td>
                                                            <th>Family Type</th><td>${userDetails.family_type || '--'}</td> 
                                                            <th>House Type</th><td>${userDetails.house_type || '--'}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Family City</th><td>${userDetails.city_family || '--'}</td>
                                                            <th>Family Income</th><td>${getIncomeRange(userDetails.family_income)} </td>
                                                            <th>Gotra</th><td>${userDetails.gotra || '--'}</td>
                                                         
                                                        </tr>
                                                        <tr>
                                                            <th>Unmarried Brothers</th><td>${userDetails.unmarried_brothers || '0'}</td>
                                                            <th>Unmarried Sisters</th><td>${userDetails.unmarried_sisters || '0'}</td>
                                                            <th>Married Brothers</th><td>${userDetails.married_brothers || '0'}</td>
                                                            <th>Married Sisters</th><td>${userDetails.married_sisters || '0'}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>About Family</th>
                                                            <td rowspan="5" colspan="7">${userDetails.about_family || ''}  </td>
                                                        </tr>
                                                </table>
                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                        }
                        $('.pdfDiv').append(htmlData);
                        $("#pdf_data").ready(function() {
                            window.print();
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>

<div class="modal fade" id="approveProfile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close close" onclick="closeModal()">
                </button>
            </div>
            <div class="modal-body">

                <div id="progressbarwizard">


                    <ul class="nav nav-pills nav-justified form-wizard-header mb-3" id="tabNav">
                        <li class="nav-item ">
                            <a href="#profile-tab-1" data-bs-toggle="tab" data-id="1" data-toggle="tab"
                                class="nav-link navActive nav1 active" id="nav1">
                                <span class="number">1</span>
                                <span class="d-none d-sm-inline">Personal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-tab-2" data-bs-toggle="tab" data-id="2" data-toggle="tab"
                                class="nav-link navActive nav2 activestage1">
                                <span class="number">2</span>
                                <span class="d-none d-sm-inline">Professional</span>
                            </a>
                            <div class="nav-link stage1">
                                <span class="number">2</span>
                                <span class="d-none d-sm-inline">Professional</span>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-tab-3" data-bs-toggle="tab" data-id="3" data-toggle="tab"
                                class="nav-link navActive nav3 activestage2">
                                <span class="number">3</span>
                                <span class="d-none d-sm-inline">Family</span>
                            </a>
                            <div class="nav-link stage2">
                                <span class="number">3</span>
                                <span class="d-none d-sm-inline">Family</span>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-tab-4" data-bs-toggle="tab" data-id="4" data-toggle="tab"
                                class="nav-link navActive nav4 activestage3">
                                <span class="number">4</span>
                                <span class="d-none d-sm-inline">Photo</span>
                            </a>
                            <div class="nav-link stage3">
                                <span class="number">4</span>
                                <span class="d-none d-sm-inline">Photo</span>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="#profile-tab-5" data-bs-toggle="tab" data-id="5" data-toggle="tab"
                                class="nav-link navActive nav5 activestage4">
                                <span class="number">5</span>
                                <span class="d-none d-sm-inline">Preferences</span>
                            </a>
                            <div class="nav-link stage4">
                                <span class="number">5</span>
                                <span class="d-none d-sm-inline">Preferences</span>
                            </div>
                        </li>
                    </ul>

                    <div class="tab-content b-0 pt-0 mb-0">

                        <div id="bar" class="progress mb-3" style="height: 7px;">
                            <div class="bar progress-bar progress-bar-striped progress-bar-animated "
                                style="width: 80%;">
                            </div>
                        </div>

                        <!-- end tab pane -->

                        {{-- Personal --}}
                        <form class="tab-pane active submitenext" action="{{ route('approvel-save-personal') }}"
                            id="profile-tab-1">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">Managed
                                            By</label>
                                        <div class="col-md-3">
                                            <input type="number" class="d-none user_data_id" name="user_data">
                                            <input type="number" class="d-none stage" name="stage" value="2">
                                            <select name="profile_creating_for" class="form-select"
                                                id="profile_creating_for" required>

                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="name1">Gender</label>
                                        <div class="col-md-3"> <select name="lead_gender"
                                                class="form-select changeAbout" id="lead_gender" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select> </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">Full
                                            name</label>
                                        <div class="col-md-3"> <input type="text" id="full_name" name="full_name"
                                                class="form-control changeAboutKey" placeholder="Full Name" required>
                                        </div> <label class="col-md-3 col-form-label" for="name1"> Food
                                            Choice</label>
                                        <div class="col-md-3">
                                            <select name="food_choice" id="food_choice" class="form-select" required>
                                                <option value="">Select Food Choice</option>
                                                <option value="Non-Vegetarian">Non-Vegetarian</option>
                                                <option value="Vegetarian">Vegetarian</option>
                                                <option value="Eggetarian">Eggetarian</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label"
                                            for="surname1">Religion</label>
                                        <div class="col-md-3">
                                            <select name="religion" class="form-select changeAbout religion" required>
                                            </select>
                                        </div> <label class="col-md-3 col-form-label" for="surname1">Castes</label>
                                        <div class="col-md-3">
                                            <select id="surname1"
                                                name="castes"class="form-select changeAbout caste">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label"
                                            for="email1">Birth
                                            Date</label>
                                        <div class="col-md-3"> @php $max_date = date('Y-m-d', strtotime('-18 years')); @endphp <input type="date"
                                                id="birth_date" name="birth_date" class="form-control"
                                                max="{{ $max_date }}" value="{{ $max_date }}" required> </div>
                                        <label class="col-md-3 col-form-label" for="email1">Birth Time</label>
                                        <div class="col-md-3">
                                            <input type="time" id="birth_time" name="birth_time"
                                                class="form-control" value="{{ date('H:i') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label"
                                            for="email1">Height</label>
                                        <div class="col-md-3">
                                            <select name="user_height" id="user_height"
                                                class="form-select user_height" required>
                                                <option value="">Select Height</option>
                                            </select>
                                        </div> <label class="col-md-3 col-form-label" for="email1">Weight
                                            (Kg)</label>
                                        <div class="col-md-3"> <input type="number" max="150" id="weight"
                                                name="weight" class="form-control" value="60" required>
                                        </div>
                                    </div>
                                    <div class="row md-3">
                                        <label class="col-md-3 col-form-label" for="maritalStatus">Marital
                                            Status</label>
                                        <div class="col-md-3">
                                            <select name="marital_status" id="maritalStatus"
                                                class="form-select changeAbout" required>
                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="no_of_child">No Of
                                            Child</label>
                                        <div class="col-md-3">
                                            <input type="number" name="no_of_child" id="no_of_child"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row md-3 mt-3">
                                        <label class="col-md-3 col-form-label"for="isdisable">Is
                                            Disable</label>
                                        <div class="col-md-3">
                                            <select name="is_disable" class="form-select" id="is_disable" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="disability">Disability</label>
                                        <div class="col-md-3">
                                            <input type="text" name="disability" id="disability"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row md-3 mt-3">
                                        <label class="col-md-3 col-form-label"for="citizenship">Citizenship</label>
                                        <div class="col-md-3">
                                            <select name="citizenship" class="form-select" id="citizenship" required>
                                                <option value="indian">Indian</option>
                                                <option value="nri">NRI</option>
                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="birth_place">Birth
                                            Place</label>
                                        <div class="col-md-3">
                                            <input type="text" name="birth_place" id="birth_place"
                                                class="form-control search_working_city changeAboutKey" required>
                                            <div class="col-md-12 cityListOptions"> </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <label class="col-md-3 col-form-label">Alternate Number1 (Optional)</label>
                                        <div class="col-md-3">
                                            <input type="text" name="alternate_number1" id="alternate_number1"
                                                class="form-control ">
                                        </div>
                                        <label class="col-md-3 col-form-label">What's App Number (Optional)</label>
                                        <div class="col-md-3">
                                            <input type="text" name="whatsapp_no" id="whatsapp_no"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label">Alternate Number2 (Optional)</label>
                                        <div class="col-md-3">
                                            <input type="text" name="alternate_number2" id="alternate_number2"
                                                class="form-control">
                                        </div>
                                        <label class="col-md-3 col-form-label">Email (Optional)</label>
                                        <div class="col-md-3">
                                            <input type="text" name="email" id="email"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="row md-3 mt-3">
                                        <label class="col-md-3 col-form-label" for="surname1">Manglik
                                            Status</label>
                                        <div class="col-md-3"> <select name="manglik_status"
                                                class="form-select changeAbout" id="manglik_status" required>
                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label">Locality</label>
                                        <div class="col-md-3">
                                            <input type="text" name="locality" id="locality"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <ul class="pager wizard mb-0 list-inline mt-2">
                                <li class="previous list-inline-item disabled">
                                    {{-- <button type="button" class="btn btn-light"><i
                                                class="mdi mdi-arrow-left me-1"></i> Back to Source</button> --}}
                                </li>
                                <li class="next list-inline-item float-end">
                                    <button type="submit" class="btn btn-success submitenext">Go To
                                        Professional Details <i class="mdi mdi-arrow-right ms-1"></i></a>
                                </li>
                            </ul>
                        </form>


                        {{-- Professional --}}
                        <form method="post" class="tab-pane submitenext"
                            action="{{ route('approvel-save-professional') }}" id="profile-tab-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="name1">
                                            Highest Degree</label>
                                        <div class="col-md-3">
                                            <select name="education_list" class="form-select changeAbout"
                                                id="education_list" required>
                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="name1">
                                            Occupation</label>
                                        <div class="col-md-3">
                                            <select name="occupation_list" class="form-select changeAbout"
                                                id="occupation_list" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="college_ug">
                                            UG College</label>
                                        <div class="col-md-3">
                                            <input type="text" name="college_ug"
                                                placeholder="Enter UG College Name" class="form-control"
                                                id="college_ug">
                                        </div>
                                        <label class="col-md-3 col-form-label" for="education_ug">
                                            UG Degree</label>
                                        <div class="col-md-3">
                                            <select name="education_ug" class="form-select" id="education_ug">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="college_pg">
                                            PG College</label>
                                        <div class="col-md-3">
                                            <input type="number" class="d-none user_data_id" name="user_data">
                                            <input type="number" class="d-none stage" name="stage"
                                                value="3">
                                            <input type="text" placeholder="Enter PG College Name"
                                                name="college_pg" class="form-control" id="college_pg">
                                        </div>
                                        <label class="col-md-3 col-form-label" for="education_pg">
                                            PG Degree</label>
                                        <div class="col-md-3">
                                            <select name="education_pg" class="form-select" id="education_pg">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="school_name">
                                            School Name </label>
                                        <div class="col-md-3">
                                            <input type="text" name="school_name"
                                                placeholder="Enter School Name  " class="form-control"
                                                id="school_name">
                                        </div>
                                        <label class="col-md-3 col-form-label" for="name1">Wish
                                            To Go
                                            Abroad</label>
                                        <div class="col-md-3"> <select name="wish_to_go_abroad" class="form-select"
                                                id="wish_to_go_abroad">
                                                <option selected value="0">No</option>
                                                <option value="1">Yes</option>
                                            </select> </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label"
                                            for="name1">Working
                                            City</label>
                                        <div class="col-md-3 row">
                                            <div class="col-md-12">
                                                <input type="text" name="search_working_city" autocomplete="off"
                                                    class="form-control search_working_city changeAboutKey"
                                                    id="search_working_city">
                                                <div class="col-md-12 cityListOptions"> </div>
                                            </div>
                                        </div> <label class="col-md-3 col-form-label" for="surname1">Yearly
                                            Income</label>
                                        <div class="col-md-3"> <select name="yearly_income" class="form-select"
                                                id="yearly_income" required>
                                                @foreach ($income_range as $annual_income)
                                                    <option value="{{ $annual_income[0] }}">
                                                        {{ $annual_income[1] }}</option>
                                                @endforeach
                                            </select> </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label"
                                            for="surname1">About</label>
                                        <div class="col-md-9">
                                            <textarea name="about_me" id="about_me" cols="10" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="compName">Company
                                            Name</label>
                                        <div class="col-md-3">
                                            <input type="text" name="company_name" id="company_name"
                                                class="form-control" required>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="designation">Designation</label>
                                        <div class="col-md-3">
                                            <input type="text" name="designation" id="designation"
                                                class="form-control changeAboutKey" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="collegeName">College
                                            Name</label>
                                        <div class="col-md-3">
                                            <input type="text" name="college_name" id="college_name"
                                                class="form-control" required>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="compName">Additional
                                            Degree</label>
                                        <div class="col-md-3">
                                            <input type="text" name="additional_degree" id="additional_degree"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <ul class="pager wizard mb-0 list-inline mt-2">
                                <li class="previous list-inline-item disabled">
                                    <button type="button" class="btn btn-light"><i
                                            class="mdi mdi-arrow-left me-1"></i> Back to
                                        Personal</button>
                                </li>
                                <li class="next list-inline-item float-end">
                                <li class="next list-inline-item float-end">
                                    <button type="submit" class="btn btn-success submitenext">Go To Family Details <i
                                            class="mdi mdi-arrow-right ms-1"></i></button>
                                </li>
                            </ul>
                        </form>

                        {{-- Family --}}
                        <form method="post" class="tab-pane submitenext"
                            action="{{ route('approvel-save-family') }}" id="profile-tab-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">
                                            Gotra</label>
                                        <div class="col-md-3"> <input type="text" id="family_gotra"
                                                name="family_gotra" class="form-control" placeholder="Gotra"
                                                required>
                                        </div> <label class="col-md-3 col-form-label" for="surname1">Family
                                            Income</label>
                                        <input type="number" class="d-none user_data_id" name="user_data">
                                        <input type="number" class="d-none stage" name="stage" value="4">
                                        <div class="col-md-3">
                                            <select name="family_income" class="form-select" id="family_income"
                                                required>
                                                @foreach ($income_range as $annual_income)
                                                    <option value="{{ $annual_income[0] }}">
                                                        {{ $annual_income[1] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label"
                                            for="name1">Father
                                            Occupation</label>
                                        <div class="col-md-3"> <select name="father_status" id="father_status"
                                                class="form-select" required>
                                                <option value="">Select Father Status</option>
                                            </select> </div> <label class="col-md-3 col-form-label"
                                            for="name1">Mother
                                            Occupation</label>
                                        <div class="col-md-3"> <select name="mother_status" id="mother_status"
                                                class="form-select" required>
                                                <option value="">Select Father Status</option>
                                            </select> </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">
                                            Unmarried Brothers</label>
                                        <div class="col-md-3"> <input type="number" min="0" value="0"
                                                class="form-control" name="brothers" id="brothers" required> </div>
                                        <label class="col-md-3 col-form-label" for="name1">
                                            Unmarried Sisters</label>
                                        <div class="col-md-3"> <input type="number" min="0" value="0"
                                                class="form-control" name="sisters" id="sisters" required> </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">
                                            Married
                                            Brothers</label>
                                        <div class="col-md-3"> <input type="number" value="0"
                                                class="form-control" name="married_brothers" id="married_brothers">
                                        </div> <label class="col-md-3 col-form-label" for="name1" required>Married
                                            Sisters</label>
                                        <div class="col-md-3"> <input type="number" value="0"
                                                class="form-control" name="married_sisters" id="married_sisters"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">
                                            House
                                            Type</label>
                                        <div class="col-md-3">
                                            <select name="house_type" id="house_type" class="form-select" required>
                                                <option value="">Select House Type</option>
                                                <option value="1" selected>Owned</option>
                                                <option value="2">Rented</option>
                                                <option value="3">Leased</option>
                                            </select>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="name1">Family
                                            Type</label>
                                        <div class="col-md-3">
                                            <select name="family_type" id="family_type" class="form-select" required>
                                                <option value="">Select Family Type</option>
                                                <option value="1" selected>Nuclear</option>
                                                <option value="2">Joint</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label" for="familyCurrCity">Family
                                            Current City</label>
                                        <div class="col-md-3">
                                            <input type="text" name="family_current_city"
                                                class="form-control search_working_city" id="family_current_city"
                                                required>
                                            <div class="col-md-12 cityListOptions"> </div>
                                        </div>
                                        <label class="col-md-3 col-form-label" for="nativeCity">Native
                                            City</label>
                                        <div class="col-md-3">
                                            <input type="text" name="native_city" id="native_city"
                                                class="form-control search_working_city" required>
                                            <div class="col-md-12 cityListOptions"> </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label">Contact Address</label>
                                        <div class="col-md-9">
                                            <textarea name="contact_address" id="contact_address" cols="10" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 col-form-label">Family About</label>
                                        <div class="col-md-9">
                                            <textarea name="family_about" id="family_about" cols="10" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <ul class="pager wizard mb-0 list-inline mt-2">
                                <li class="previous list-inline-item disabled form_output">
                                    <button type="button" class="btn btn-light"><i
                                            class="mdi mdi-arrow-left me-1"></i> Back to
                                        Profession</button>
                                </li>
                                <li class="next list-inline-item float-end btn_div">
                                    <button type="submit" class="btn btn-success submitenext">Go To Photos <i
                                            class="mdi mdi-arrow-right ms-1"></i></button>
                                </li>
                            </ul>
                        </form>

                        {{-- Photo --}}
                        <form method="post" class="tab-pane submitenext"
                            action="{{ route('approvel-save-photo') }}" id="profile-tab-4">
                            <div class="row">
                                <input type="number" class="d-none user_data_id" name="user_data">
                                <input type="number" class="d-none stage" name="stage" value="5">
                                <div class="col-12 photo_viewer row"> </div> <!-- end col -->
                            </div> <!-- end row -->
                            <div class="row mb-3">
                                <label class="col-md-3 col-form-label">Photo Score</label>
                                <div class="col-md-3">
                                    <select name="photo_score" id="photo_score" class="form-select" required>
                                        <option value="">Select Photo Score</option>
                                        <option value="0" selected>0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                                <label class="col-md-3 col-form-label">Photo Upload</label>
                                <div class="col-md-3">
                                    <input type="file" name="photo" accept="image/*" class="js-photo-upload"
                                        data-id="" id="userPhoto" userId="">
                                    <input type="hidden" name="photo_num" id="photo_num">
                                </div>
                            </div>

                            <ul class="pager wizard mb-0 list-inline mt-2">
                                <li class="previous list-inline-item disabled form_output">
                                    <button type="button" class="btn btn-light"><i
                                            class="mdi mdi-arrow-left me-1"></i> Back to
                                        Family</button>
                                </li>
                                <li class="next list-inline-item float-end btn_div">
                                    <button type="submit" class="btn btn-success submitenext">Go To Preferences <i
                                            class="mdi mdi-arrow-right ms-1"></i></button>
                                </li>
                            </ul>
                        </form>
                        {{-- preference --}}
                        <form method="post" action="{{ route('approvel-save-userpreferences') }}"
                            autocomplete="off" class="form-horizontal was-validated tab-pane" data-id="profile-tab-5"
                            id="profile-tab-5">
                            @csrf
                            <div class="row">
                                <div class="row mb-3">
                                    <label class="col-md-3 col-form-label" for="surname1">Religion Preference</label>
                                    <div class="col-md-3">
                                        <input type="number" class="d-none user_data_id" name="user_data">
                                        <input type="number" class="d-none stage" name="stage" value="6">
                                        <select name="religion_preference[]" class="form-select"
                                            id="religion_preference" multiple> </select>
                                    </div>
                                    <label class="col-md-3 col-form-label" for="name1">
                                        Caste Preference</label>
                                    <div class="col-md-3"> <select class="form-select col-md-12 casteSelector"
                                            id="castes_pref" name="caste_perf_lists[]" multiple>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1"> Min
                                        Age</label>
                                    <div class="col-md-3"> <input type="number" name="min_age" id="min_age"
                                            class="form-control" required> </div> <label
                                        class="col-md-3 col-form-label" for="name1">
                                        Max Age</label>
                                    <div class="col-md-3"> <input type="number" name="max_age" id="max_age"
                                            class="form-control" required> </div>
                                </div>
                                <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1"> Min
                                        Height</label>
                                    <div class="col-md-3">
                                        <select name="min_height" id="min_height" class="form-select user_height"
                                            required>
                                            <option value="">Select Height</option>
                                        </select>
                                    </div> <label class="col-md-3 col-form-label" for="name1">
                                        Max
                                        Height</label>
                                    <div class="col-md-3">
                                        <select name="max_height" id="max_height" class="form-select user_height"
                                            required>
                                            <option value="">Select Height</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-md-3 col-form-label">Min Income</label>
                                    <div class="col-md-3">
                                        <select name="min_income" class="form-select" id="min_income" required>
                                            @foreach ($income_range as $annual_income)
                                                <option value="{{ $annual_income[0] }}">
                                                    {{ $annual_income[1] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-md-3 col-form-label">Max Income</label>
                                    <div class="col-md-3">
                                        <select name="max_income" class="form-select" id="max_income" required>
                                            @foreach ($income_range as $annual_income)
                                                <option value="{{ $annual_income[0] }}">
                                                    {{ $annual_income[1] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1">
                                        Manglik</label>
                                    <div class="col-md-3"> <select name="manglik_pref" id="manglik_pref"
                                            class="form-select"></select> </div>
                                    <label class="col-md-3 col-form-label" for="name1"> Food
                                        Choice</label>
                                    <div class="col-md-3"> <select name="foodchoice_pref" id="foodchoice_pref"
                                            class="form-select" required>
                                            <option value="0">Doesn't Matter</option>
                                            <option value="1">Non Vegetarial</option>
                                            <option value="2">Vegetarial</option>
                                        </select> </div>
                                </div>
                                <div class="row mb-3"> <label class="col-md-3 col-form-label" for="name1"> Marital
                                        Status</label>
                                    <div class="col-md-3">
                                        <select name="marital_status_perf" id="marital_status_perf"
                                            class="form-select"> </select>
                                    </div>
                                    <label class="col-md-3 col-form-label" for="name1" required>
                                        Occupation</label>
                                    <div class="col-md-3"> <select name="occupation_status_perf"
                                            id="occupation_status_perf" class="form-select" required> </select>
                                    </div>
                                </div>
                                <div class="row md-3 mt-2">
                                    <label class="col-md-3 col-form-label">Citizenship Preference</label>
                                    <div class="col-md-3">
                                        <select name="citizenship_pref" class="form-select" id="citizenship_pref"
                                            required>
                                            <option value="indian">Indian</option>
                                            <option value="nri">NRI</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div> <!-- end row -->
                            <ul class="pager wizard mb-0 list-inline mt-2">
                                <li class="previous list-inline-item disabled form_output">
                                    <button type="button" class="btn btn-light"><i
                                            class="mdi mdi-arrow-left me-1"></i>
                                        Back to Photos</button>
                                </li>
                                <li class="list-inline-item float-end btn_div">
                                    <a href="#" target="_blank" name="submit" id="print_user"
                                        class="btn btn-success">Print</a>
                                    <button type="submit" name="submit" class="btn btn-success">Approve</button>
                                </li>
                            </ul>
                        </form>
                    </div> <!-- tab-content -->
                </div> <!-- end #progressbarwizard-->

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <h5 class="modal-title  text-white">Upload User Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="number" name="user_id_photo" id="user_id_photo" class="d-none">
                    <div class="form-group">
                        <input type="text" name="user_imagesixfour" class="form-control d-none"
                            id="user_imagesixfour">
                    </div>
                    <div class="form-group">
                        <div class="modal-positioner">
                            <img style="width: 271px; height: 271px;" class="js-avatar-preview" src="">
                        </div>
                    </div>
                    <div class="form-group d-none">
                        <img id="avatar-crop" src="" style="width: 384px; height: 512px;">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary js-save-cropped-avatar">Save and Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

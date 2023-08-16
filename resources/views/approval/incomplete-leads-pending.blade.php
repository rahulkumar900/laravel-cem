@section('page-title', 'Incomplete Leads Pending')
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
                    <h4 class="page-title">Incomplete Leads Pending</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pending Profiles</a></li>
                            <li class="breadcrumb-item active">Incomplete Leads Pending</li>
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
                            <table class="table table-striped table-inverse" id="incomplete_leads_pending_table">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Id</th>
                                        <th>Phone</th>
                                        <th>Channel</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="load_incomplete_leads_pending_data">
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

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
    @include('form.modelProgressForm', ['data' => 'test', 'new' => 'name', 'title' => 'Update Profile'])
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
        integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadAllreligion() {
                var religsion_data = []
                var religion_html = '';
                $.ajax({
                    url: "{{ route('allreligion') }}",
                    type: "get",
                    success: function(religion_resp) {
                        for (let q = 0; q < religion_resp.length; q++) {
                            const religsion_data = religion_resp[q];
                            religion_html +=
                                `<option value="${religsion_data.id}@${religsion_data.religion}" >${religsion_data.religion}</option>`;

                        }
                        $('#religion_preference').html(religion_html)
                        $('#religion_preference').chosen()
                    }
                });
            }

            function loadAllCastes() {
                var data = data
                var caste_html = '';
                $.ajax({
                    url: "{{ route('getallcastes') }}",
                    type: "get",
                    success: function(caste_Response) {
                        for (let k = 0; k < caste_Response.length; k++) {
                            const caste_list = caste_Response[k];
                            if (caste_list.id != 0) {
                                caste_html +=
                                    `<option value="${caste_list.id}@${caste_list.caste ?? caste_list.value}">${caste_list.caste ?? caste_list.value}</option>`;
                            }
                        }
                        $('#castes_pref').html(caste_html)
                        $('#castes_pref').chosen()
                    }
                });
            }
            loadAllCastes()
            loadAllreligion()
            // $('#religion_preference').select2({
            //     ajax: {
            //         url: "{{ route('allreligion') }}",
            //         dataType: 'json'
            //         // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            //     }
            // });
            // loadAllCastes(multipule = false, id = "#castes_pref", url =
            //     "{{ route('getallcastes') }}") //this function will load all caste for persnol stage
            // loadReligion(multipule = false, id = "#religion_preference", url =
            //     "{{ route('allreligion') }}")
            // $("#castes_pref").chosen()
            // $("#religion_preference").chosen()
            // load profiles for approval
            var table_data = $('#incomplete_leads_pending_table').DataTable({
                "order": [
                    [4, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('incompleteleadspendinglist') }}",
                    "type": "get",
                },
                "columns": [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'user_phone',
                        name: 'user_phone',
                    },
                    {
                        data: 'channel',
                        name: 'channel',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: function(data) {
                            return `<button data-toggle="tooltip" data-placement="top" title="Created At : ${data.created_at}"  data-mobile="${data.user_phone}" class="btn btn-primary btn-sm aprove">  <span style="color: white;">Aprove</span>  </button>`
                        },
                    }
                ]
            });
            $(document).on('click', '.aprove', function(e) {
                $(this).prop('disabled', true)
                stage = 1
                $('form').each(function() {
                    this.reset();
                });
                id = $(this).data('mobile');
                url = "{{ route('saveincompleteleads') }}"
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: "post",
                    url: url,
                    data: {
                        'mobile': id
                    },
                    success: function(responseData, textStatus, jqXHR) {
                        if (responseData != 0) {
                            $('.user_data_id').val(responseData);
                            $('#userPhoto').attr('data-id', responseData)
                            $('#userPhoto').attr('userId', responseData)
                            $('#approveProfile').modal('show');
                        }
                    }
                })
            })
        });
    </script>
@endsection

@section('page-title', 'Manage Caste')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-left">
                        <h4 class="page-title">Welcome !</h4>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Facebook</a></li>
                            <li class="breadcrumb-item active add_caste_link">Add Caste</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Manage Caste</h4>
                        <div class="col-md-12">
                            <table class="table table-striped table-inverse " id="caste-list-table">
                                <thead class="thead-inverse">
                                    <tr>
                                        <th>Caste</th>
                                        <th>Religion</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="caste_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Caste</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('savecaste') }}" method="post" id="add_caste_form">
                        @csrf
                        <div class="form-group mb-2">
                            <select name="religion" id="religion" class="form-select" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="caste_name" class="form-control" placeholder="Caste Name" required>
                        </div>
                        <div class="form-group form_output mb-3">

                        </div>
                        <div class="form-group text-right submit_div">
                            <button class="btn btn-sm btn-danger float-end" type="submit" name="submit">Add
                                Caste</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Edit caste modal --}}
    <div class="modal fade" id="update_caste" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Update Caste</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updatecaste') }}" method="post" id="update_caste_form">
                        @csrf
                        <input type="text" name="caste_id" class="d-none" id="caste_id">
                        <div class="form-group mb-2">
                            <select name="religion" id="religion_update" class="form-select" required>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="caste_name" id="caste_name" class="form-control" placeholder="Caste Name" required>
                        </div>
                        <div class="form-group form_output_update mb-3">

                        </div>
                        <div class="form-group text-right submit_div_u">
                            <button class="btn btn-sm btn-warning float-end" type="submit" name="submit">Update Caste</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            $(document).on('click', '.add_caste_link', function(e) {
                $('#caste_modal').modal('show');
                $('.form_output').html('');
            });

            $(document).on('click', '.edit_button', function(e) {
                $('#update_caste').modal('show');
            });

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
                        $('#religion_update').html(religion_html);
                    }
                });
            }

            var table_data = $('#caste-list-table').DataTable({
                "order": [
                    [1, "desc"]
                ],
                "processing": true,
                "ajax": "{{ route('showcastelist') }}",
                "columns": [{
                        data: 'value',
                    },
                    {
                        data: null,
                        render : function(data){
                            if (data.religion != null) {
                                return data.religion.religion;
                            } else {
                                return "NA";
                            }
                        }
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'updated_at',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<button class="btn btn-xs btn-warning edit_button" caste_id="${data.id}" caste_name="${data.value}" religion_id="${data.religion_id}"><i class="fas fa-pencil-ruler"></i></button>`;
                        }
                    }
                ]
            });

            $(document).on('submit', '#add_caste_form', function(e) {
                e.preventDefault();
                $('.submit_div').html(
                    '<div class="spinner-grow text-success" role="status"><span class="visually-hidden">Saving...</span></div>'
                );
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(dataSave) {
                        if (dataSave.type == true) {
                            $('.form_output').html(
                                `<div class="alert alert-sucess" role="alert"><strong>Sucess</strong> ${dataSave.message}</div>`
                            );
                            $('#add_caste_form')[0].reset();
                            $('#caste_modal').modal('hide');
                            table_data.ajax.reload();
                        } else {
                            $('.form_output').html(
                                `<div class="alert alert-danger" role="alert"><strong>Sucess</strong> ${dataSave.message}</div>`
                            );
                        }
                        $('.submit_div').html(
                            '<button class="btn btn-sm btn-danger text-right" type="submit" name="submit">Add Caste</button>'
                        );
                    }
                });
            });

            $(document).on('click','.edit_button',function(e){
                e.preventDefault();
                $('.form_output_update').html('');
                $('#caste_id').val($(this).attr('caste_id'));
                $('#caste_name').val($(this).attr('caste_name'));
                if ($(this).attr('religion_id') >0) {
                    $('#religion_update').val($(this).attr('religion_id'));
                }
               $('#update_caste').modal('show');
            });

            $(document).on('submit', '#update_caste_form', function(e) {
                e.preventDefault();
                $('.submit_div_u').html(
                    '<div class="spinner-grow float-end text-success" role="status"><span class="visually-hidden">Saving...</span></div>'
                );
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(dataSave) {
                        if (dataSave.type == true) {
                            $('.form_output_update').html(
                                `<div class="alert alert-success" role="alert"><strong>Sucess</strong> ${dataSave.message}</div>`
                            );
                            $('#add_caste_form')[0].reset();
                            window.setTimeout(() => {
                                    $('#update_caste').modal('hide');
                            }, 3000);
                            table_data.ajax.reload();
                        } else {
                            $('.form_output_update').html(`<div class="alert alert-danger" role="alert"><strong>Alert</strong> ${dataSave.message}</div>`);
                        }
                        $('.submit_div_u').html('<button class="btn btn-sm btn-warning float-end" type="submit" name="submit">Update Caste</button>');
                    }
                });
            });
        });
    </script>
@endsection

@section('page-title', 'Open Tickets')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title text-capitalize">{{ $_GET['record_type'] }} Ticket Details Tickets !</h4>
                    <div class="page-title-left">
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item addTicketButton"><a href="javascript: void(0);">Add Ticket</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Care</a></li>
                            <li class="breadcrumb-item active text-capitalize">{{ $_GET['record_type'] }} Tickets</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <h4 class="header-title">Ticket List <span class="waiting_text"></span></h4>
                        <table id="salescrm-table" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#Ticekt Id</th>
                                    <th>Priority</th>
                                    <th>Subject</th>
                                    <th>Assigned To</th>
                                    <th>User</th>
                                    <th>Mobile</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Last Update</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>

    {{-- resolve comment modal starts --}}
    <div class="modal fade" id="resolveModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resolve Comment</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('resolveticket') }}" method="post" id="resolveCommentForm">
                        @csrf
                        <input type="number" class="d-none" id="ticket_id" name="ticekt_id">
                        <div class="form-group mb-2">
                            <textarea name="resolve_comment" id="" class="form-control" placeholder="enter Comment" required></textarea>
                        </div>
                        <div class="form-group form_output mb-2"></div>
                        <div class="form-group mb-2 float-end">
                            <button type="submit" name="submit" class="btn btn-success">Resolve Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- resolve comment end modal --}}

    {{-- Add ticket model starts --}}
    <div class="modal fade" id="addTicektModel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Ticket</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addnewticket') }}" method="post" id="submitTicketform" autocomplete="off">
                        @csrf
                        <input type="number" name="user_id" id="searched_user_id" class="d-none">
                        <div class="form-group mb-2">
                            <label for="">Ticket Id</label>
                            <input type="text" id="ticekt_id_generated" class="form-control" name="ticekt_id_generated"
                                readonly>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="client_name">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Mobile</label>
                            <input type="number" class="form-control" name="client_mobile" id="client_mobile">
                            <span class="userSearchResult"></span>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Subject</label>
                            <input type="text" class="form-control" name="subject">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Message</label>
                            <textarea name="message" class="form-control"></textarea>
                        </div>
                        <div class="form-group form_output mb-2">
                        </div>
                        <div class="form-group float-end mb-2 saveButtonDiv" style="display: none">
                            <button type="submit" name="submit" class="btn btn-warning">Save Ticket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- add ticket model ends --}}
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(".alert").alert();
        $(document).ready(function() {
            var table_data = $('#salescrm-table').DataTable({
                "order": [
                    [5, "asc"]
                ],
                "processing": true,
                "ajax": {
                    "url": "{{ route('detailedviewdata') }}",
                    "type": "get",
                    "data": function(dataType) {
                        dataType.data_types = "{{ $_GET['record_type'] }}";
                    }
                },
                "columns": [{
                        data: null,
                        render: function(data) {
                            var returnHtml = '';
                            if (data.ticket_priority == 'very high') {
                                returnHtml +=
                                    `<span class="badge bg-danger">${data.ticket_no}</span>`;
                            } else if (data.ticket_priority == 'high') {
                                returnHtml +=
                                    `<span class="badge badge badge-soft-info">${data.ticket_no}</span>`;
                            } else if (data.ticket_priority == 'medium') {
                                returnHtml +=
                                    `<span class="badge bg-secondary text-light">${data.ticket_no}</span>`;
                            } else if (data.ticket_priority == 'low') {
                                returnHtml +=
                                    `<span class="badge bg-success">${data.ticket_no}</span>`;
                            }
                            return returnHtml;
                        }
                    },
                    {
                        data: 'ticket_priority',
                    },
                    {
                        data: 'subject',
                    },
                    {
                        //data: 'subject',
                        data: null,
                        render : function(data){
                            if(data.users != null){
                                return data.users.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    {
                        //data: 'name',
                        data: null,
                        render : function(data){
                            if(data.user_data != null){
                                return data.user_data.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    {
                        data: 'mobile',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var commentsHtml = '';
                            for (let i = 0; i < data.comments.length; i++) {
                                const comment = data.comments[i];
                                commentsHtml += `<a href="#ticket${data.ticket_no}" data-toggle="collapse">View</a>
                                    <div id="ticket${data.ticket_no}" class="collapse">
                                    ${comment.comments}
                                    </div>`;
                            }
                            return commentsHtml;
                        }
                    },
                    {
                        data: 'ticket_status',
                    },
                    {
                        data: null,
                        render: function(data) {
                            var splitDate = data.due_date.split(' ')[0];
                            var dateHtml = '';
                            if (data.due_date.split(' ')[0] == "{{ date('Y-m-d') }}") {
                                dateHtml =
                                    `<span class="text-warning">${data.due_date.split(' ')[0]}</span>`;
                            } else if (data.due_date.split(' ')[0] < "{{ date('Y-m-d') }}") {
                                dateHtml =
                                    `<span class="text-danger">${data.due_date.split(' ')[0]}</span>`;
                            } else {
                                dateHtml = `<span>${data.due_date.split(' ')[0]}</span>`;
                            }
                            return dateHtml;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.resolved_on != null) {
                                return data.resolved_on.split(' ')[0];
                            } else {
                                return 'N.A';
                            }
                        }
                    }
                ]
            });


            $(document).on('click', '.addTicketButton', function(e) {
                e.preventDefault();
                $('#addTicektModel').modal('show');
            });

            $(document).on('submit', '#submitTicketform', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(ticketResp) {
                        if (ticketResp.type == true) {
                            $('.form_output').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Success</strong> ${ticketResp.message}
                                </div>`);
                            $('#submitTicketform')[0].reset();
                            table_data.ajax.reload();
                        } else {
                            $('.form_output').html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Warning</strong> ${ticketResp.message}
                                </div>`);
                        }
                    }
                });
            });

            $(document).on('change', '#client_mobile', function() {
                var mobile = $(this).val();
                $('.userSearchResult').html('');
                $('#client_mobile').attr('readonly', false);
                $.ajax({
                    url: "{{ route('serachbymobile') }}",
                    type: "get",
                    data: {
                        'user_mobile': mobile
                    },
                    success: function(searchResponse) {
                        var htmlResponseUser = '';
                        if (searchResponse != null && searchResponse.id > 0) {
                            $('.saveButtonDiv').show();
                            $('#searched_user_id').val(searchResponse.id);
                            $('#client_mobile').val(searchResponse.user_mobile);
                            $('#client_mobile').attr('readonly', true);
                            var genTicketId = generateTicketNo(4);
                            $('#ticekt_id_generated').val(genTicketId);
                        } else {
                            htmlResponseUser +=
                                '<p class="text-danger">No User Fiund For This Mobile. Try Again!!!</p>';
                        }
                        $('.userSearchResult').html(htmlResponseUser);
                    }
                });
            });

            function generateTicketNo(length) {
                var idstr = String.fromCharCode(Math.floor(Math.random() * 25 + 65));
                do {
                    var ascicode = Math.floor(Math.random() * 42 + 48);
                    if (ascicode < 58 || ascicode > 64) {
                        idstr += String.fromCharCode(ascicode);
                    }
                } while (idstr.length < length);
                return idstr;
            }
        });
    </script>
@endsection

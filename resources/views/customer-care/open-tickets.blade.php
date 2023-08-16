@section('page-title', 'Open Tickets')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Open Tickets !</h4>
                    <div class="page-title-left">
                        <button class="btn btn-sm btn-purple addCreditButton">Add Credit</button>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item addTicketButton"><a href="javascript: void(0);">Add Ticket</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Care</a></li>
                            <li class="breadcrumb-item active">Open Tickets</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- counting starts --}}

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-tag font-24"></i>
                                        <h3 class="total_tickets">25563</h3>
                                        <a href="{{ route('detailedview') }}?record_type=all" target="_blank">
                                            <p class="text-uppercase mb-1 font-13 fw-medium">Total tickets</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-archive font-24"></i>
                                        <h3 class="text-warning pending_tickets">6952</h3>
                                        <a href="{{ route('detailedview') }}?record_type=pending" target="_blank">
                                            <p class="text-uppercase mb-1 font-13 fw-medium">Pending Tickets</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-shield font-24"></i>
                                        <h3 class="text-success closed_ticekts">18361</h3>
                                        <a href="{{ route('detailedview') }}?record_type=closed" target="_blank">
                                            <p class="text-uppercase mb-1 font-13 fw-medium">Closed Tickets</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-delete font-24"></i>
                                        <h3 class="text-danger ongoing_tickets">250</h3>
                                        <a href="{{ route('detailedview') }}?record_type=open" target="_blank">
                                            <p class="text-uppercase mb-1 font-13 fw-medium">Ongoing Tickets</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- counting ends --}}
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
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Mobile</th>
                                    <th>Complaint Date</th>
                                    <th>Due Date</th>
                                    <th>Comment</th>
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
                            <button type="submit" name="submit" class="btn btn-success">Save</button>
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
                    <form action="{{ route('addnewticket') }}" method="post" id="submitTicketform"
                        autocomplete="off">
                        @csrf
                        <input type="number" name="user_id" id="searched_user_id" class="d-none">
                        <div class="form-group mb-2">
                            <label for="">Ticket Id</label>
                            <input type="text" id="ticekt_id_generated" class="form-control"
                                name="ticekt_id_generated" readonly>
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
                            <select name="subject" id="subject" class="form-select">

                            </select>
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

    {{-- all ticket modal starts --}}
    <div class="modal fade" id="ticektDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">All Tickets</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body table-responsive">
                    <table class="table table-striped table-inverse">
                        <thead class="thead-inverse">
                            <tr>
                                <th width="20%">Subject</th>
                                <th>Complain Date</th>
                                <th>Due Date</th>
                                <th>Last Updated</th>
                                <th>Status</th>
                                <th width="20%">Comments</th>
                            </tr>
                        </thead>
                        <tbody style="height: 30vh; overflow: scroll" class="ticektDetailsData">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- All ticket modal ends --}}

    {{-- Add Credit Starts --}}
    <div class="modal fade" id="addCreditModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Credit</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addusercredit') }}" method="post" id="addCreditForm">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="" class="col-md-3">Enter Mobile</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="user_mobile" id="user_mobile">
                                <input type="number" class="form-control d-none" name="user_id" id="user_id_fetched">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="" class="col-md-3">Total Credits</label>
                            <div class="col-md-9">
                                <input type="number" value="5" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-3 formOutputDiv">
                        </div>
                        <div class="form-group float-end submitDiv" style="display: none">
                            <button class="btn btn-sm btn-danger" type="submit" name="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Credit Ends --}}
@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(".alert").alert();
        $(document).ready(function() {

            loadTicketsCounts();

            function loadTicketsCounts() {
                $.ajax({
                    url: "{{ route('getallticketcounts') }}",
                    type: "get",
                    success: function(ticketResp) {
                        $('.total_tickets').text(ticketResp.all_ticket);
                        $('.pending_tickets').text(ticketResp.pending_ticekt);
                        $('.closed_ticekts').text(ticketResp.closed_ticket);
                        $('.ongoing_tickets').text(ticketResp.open_ticekt);
                    }
                });
            }

            var table_data = $('#salescrm-table').DataTable({
                "order": [
                    [5, "asc"]
                ],
                "processing": true,
                "ajax": "{{ route('getallopentickets') }}",
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
                        data: null,
                        render: function(data) {
                            return `<button class="btn btn-sm btn-success resolveHere" data-toggle="tooltip" data-placement="top" title="Resolve Here" ticektId="${data.id}"><i class="fa fa-check btn-success" aria-hidden="true"></i></button> <button class="btn btn-sm btn-secondary assignToMe" data-toggle="tooltip" data-placement="top" title="Assign To Me" ticektId="${data.id}"><i class="fa fa-list" aria-hidden="true"></i></button>`;
                        }
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<span class="userMobile" mobile="${data.mobile}">${data.mobile}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return new Date(data.created_at).toLocaleString();
                        }
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
                            return `<a href="#ticket${data.ticket_no}" data-toggle="collapse">View</a>
                                    <div id="ticket${data.ticket_no}" class="collapse">
                                    ${data.comments[0].comments}
                                    </div>`;
                        }
                    }
                ]
            });

            $(document).on('click', '.resolveHere', function() {
                var ticketId = $(this).attr('ticektId');
                $('#ticket_id').val(ticketId);
                $('#resolveModal').modal('show');
            });

            $(document).on('submit', ' #resolveCommentForm', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(submitResponse) {
                        var htmlRespomse = '';
                        if (submitResponse.type == true) {
                            htmlRespomse += `<div class="alert alert-success" role="alert">
                                                <strong>Success</strong> ${submitResponse.message}
                                            </div>`;
                            $('#resolveCommentForm')[0].reset();
                            table_data.ajax.reload();
                            loadTicketsCounts();
                        } else {
                            htmlRespomse += `<div class="alert alert-danger" role="alert">
                                                <strong>Success</strong> ${submitResponse.message}
                                            </div>`;
                        }
                        $('.form_output').html(htmlRespomse);
                        window.setTimeout(() => {
                            $('.form_output').html('');
                        }, 2000);
                    }
                });
            });

            $(document).on('click', '.assignToMe', function(e) {
                e.preventDefault();
                $('.waiting_text').text('Waiting...........');
                $.ajax({
                    url: "{{ route('assignopenticket') }}",
                    type: "get",
                    data: {
                        "ticekt_id": $(this).attr('ticektid')
                    },
                    success: function(responseAssign) {
                        $('.waiting_text').text('');
                        alert(responseAssign.message);
                        table_data.ajax.reload();
                        loadTicketsCounts();
                    }
                });
            });

            $(document).on('click', '.addTicketButton', function(e) {
                e.preventDefault();
                $('#client_mobile').attr('readonly', false);
                $('.saveButtonDiv').hide();
                $('#submitTicketform')[0].reset();
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
                                '<p class="text-danger">No User Found For This Mobile. Try Again!!!</p>';
                            $('.saveButtonDiv').show();
                            $('#client_mobile').attr('readonly', true);
                            var genTicketId = generateTicketNo(4);
                            $('#ticekt_id_generated').val(genTicketId);
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

            $(document).on('click', '.userMobile', function(e) {
                e.preventDefault();
                const mobile = $(this).attr('mobile');
                $.ajax({
                    type: "get",
                    data: {
                        "mobile": mobile
                    },
                    url: "{{ route('getallusertickets') }}",
                    success: function(allticketData) {
                        var htmlticektData = "";
                        for (let i = 0; i < allticketData.length; i++) {
                            const tiocketData = allticketData[i];
                            htmlticektData +=
                                `<tr>
                                            <td>${tiocketData.subject}</td>
                                            <td>${new Date(tiocketData.created_at).toLocaleString()}</td>
                                            <td>${new Date(tiocketData.due_date).toLocaleString()}</td>
                                            <td>${new Date(tiocketData.updated_at).toLocaleString()}</td>
                                            <td>${tiocketData.ticket_status}</td>
                                            <td><button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#comment${i}">View</button> <div id="comment${i}" class="collapse">`;
                            for (let j = 0; j < tiocketData.comments.length; j++) {
                                const comments = tiocketData.comments[j];
                                htmlticektData += `<p>${ new Date(comments.created_at).toLocaleString() } : ${comments.comments}</p>`;
                            }
                            //${tiocketData.ticket_status}
                            htmlticektData += ` </div></td>
                                    </tr>`;
                        }
                        $('.ticektDetailsData').html(htmlticektData);
                        $('#ticektDetailsModal').modal('show');
                    }
                });
            });

            loadCaomplainCategories();

            function loadCaomplainCategories() {
                $.ajax({
                    type: "get",
                    url: "{{ route('getcomplaincategory') }}",
                    success: function(dataCategory) {
                        var catHtml = '';
                        for (let l = 0; l < dataCategory.length; l++) {
                            const catHtmlResp = dataCategory[l];
                            catHtml +=
                                `<option value="${catHtmlResp.name}">${catHtmlResp.name}</option>`;
                        }
                        $('#subject').html(catHtml);
                    }

                });
            }

            $(document).on('click', '.addCreditButton', function(e) {
                e.preventDefault();
                $('#addCreditModal').modal('show');
            });

            $(document).on('blur', '#user_mobile', function(e) {
                $.ajax({
                    url: "{{ route('serachbymobile') }}",
                    type: "get",
                    data: {
                        "user_mobile": $(this).val()
                    },
                    success: function(userResp) {
                        let htmlData = '';
                        if (userResp.id > 0) {
                            htmlData += `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Success </strong>Record Found
                            </div>`;
                            $('#user_id_fetched').val(userResp.id);
                            $('.submitDiv').show();
                        } else {
                            htmlData += `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Alert </strong> No Record Found
                            </div>`;
                            $('#user_id_fetched').val('');
                        }
                        $('.formOutputDiv').html(htmlData);
                    }
                })
            });

            $(document).on('submit', '#addCreditForm', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serialize(),
                    success: function(creditResp) {
                        if (creditResp.type == true) {
                            $('.formOutputDiv').html(
                                `<div class="alert alert-success" role="alert"><strong>Success </strong>${creditResp.message}</div>`
                                );
                            $('#addCreditForm')[0].reset();
                            window.setTimeout(function() {
                                $('.formMessage').html('');
                                $('#addCreditModal').modal('hide');
                            }, 2000);
                        }
                    }
                })
            });
        });
        $(".alert").alert();
    </script>
@endsection

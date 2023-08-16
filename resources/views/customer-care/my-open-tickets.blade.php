@section('page-title', 'Open Tickets')
@extends('layouts.main-landingpage')
@section('page-content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">My Tickets !</h4>
                    <div class="page-title-left">
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
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
                                        <p class="text-uppercase mb-1 font-13 fw-medium">Total tickets</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-archive font-24"></i>
                                        <h3 class="text-warning pending_tickets">6952</h3>
                                        <p class="text-uppercase mb-1 font-13 fw-medium">Pending Tickets</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-shield font-24"></i>
                                        <h3 class="text-success closed_ticekts">18361</h3>
                                        <p class="text-uppercase mb-1 font-13 fw-medium">Closed Tickets</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-3">
                                    <div class="py-1">
                                        <i class="fe-delete font-24"></i>
                                        <h3 class="text-danger ongoing_tickets">250</h3>
                                        <p class="text-uppercase mb-1 font-13 fw-medium">Ongoing Tickets</p>
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
                                    <th>Subject</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Comments</th>
                                    <th>Mobile</th>
                                    <th>Complaint Date</th>
                                    <th>Due Date</th>
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
                    <form action="{{ route('updateticket') }}" method="post" id="resolveCommentForm">
                        @csrf
                        <input type="number" class="d-none" id="ticket_id" name="ticekt_id">
                        <div class="form-group mb-2">
                            <textarea name="resolve_comment" id="" class="form-control" placeholder="enter Comment"
                                required></textarea>
                        </div>
                        <div class="form-group mb-2">
                           <select name="ticketstatus" class="form-select" id="">
                               <option value="">Select Status</option>
                               <option value="pending" selected>Pending</option>
                               <option value="closed">Closed</option>
                           </select>
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

    {{-- all ticket modal starts --}}
    <div class="modal fade" id="ticektDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                <th>Subject</th>
                                <th>Complain Date</th>
                                <th>Due Date</th>
                                <th>Last Updated</th>
                                <th>Status</th>
                                <th>Comments</th>
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


@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            loadTicketsCounts();

            function loadTicketsCounts() {
                $.ajax({
                    url: "{{ route('getmyticketcounts') }}",
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
                "ajax": "{{ route('getmyopentickets') }}",
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
                        data: 'subject',
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `<button class="btn btn-sm btn-success resolveHere" data-toggle="tooltip" data-placement="top" title="Resolve Here" ticektId="${data.id}"><i class="fa fa-check btn-success" aria-hidden="true"></i></button>`;
                        }
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: null,
                        render : function(data){
                            var templeId = "{{Auth::user()->teple_id}}";
                            var htmlData = '';
                            htmlData += `<a data-toggle="collapse" data-target="#divId${data.id}">Show</a><div id="divId${data.id}" class="collapse">`;
                            for (let q = 0; q < data.comments.length; q++) {
                                const comments = data.comments[q];
                                if(comments.commented_by==templeId){
                                    htmlData += `<p>You : ${comments.comments}</p>`;
                                }else{
                                    htmlData += `<p>You : ${comments.comments}</p>`;
                                }

                            }
                            htmlData += `</div>`;

                            return htmlData;
                        }
                    },
                    {
                        data: null,
                        render : function(data){
                            return `<span class="userMobile" mobile="${data.mobile}">${data.mobile}</span>`;
                        }
                    },
                    {
                        data: null,
                        render : function(data){
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

            $(document).on('click','.userMobile',function(e){
                e.preventDefault();
                const mobile = $(this).attr('mobile');
                $.ajax({
                    type : "get",
                    data : {"mobile":mobile},
                    url : "{{route('getallusertickets')}}",
                    success :function(allticketData){
                        var htmlticektData = "";
                        for (let i = 0; i < allticketData.length; i++) {
                            const tiocketData = allticketData[i];
                            htmlticektData += `<tr>
                                            <td>${tiocketData.subject}</td>
                                            <td>${new Date(tiocketData.created_at).toLocaleString()}</td>
                                            <td>${new Date(tiocketData.due_date).toLocaleString()}</td>
                                            <td>${new Date(tiocketData.updated_at).toLocaleString()}</td>
                                            <td>${tiocketData.ticket_status}</td>
                                            <td><button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#comment${i}">View</button> <div id="comment${i}" class="collapse">`;
                                                for (let j = 0; j < tiocketData.comments.length; j++) {
                                                    const comments = tiocketData.comments[j];
                                                     htmlticektData += `<p>${ new Date(comments.created_at).toLocaleString() } : ${comments.comments}</p>
                                                                       `;
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
        });
    </script>
@endsection

@section('page-title', 'Login Other Account')
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
                            <li class="breadcrumb-item active">Add Token</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body" style="overflow: scroll">
                        <h4 class="header-title">Add New Token</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <form action="{{ route('savefbtoken') }}" method="POST" name="fb_channel"
                                    id="fbchannelform">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="facebook_leads_name">Name</label>
                                        <input type="text" class="form-control" id="facebook_leads_name"
                                            name="facebook_leads_name" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="facebook_leads_add_id">Ad Id</label>
                                        <input type="text" class="form-control" id="facebook_leads_add_id"
                                            name="facebook_leads_add_id" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="facebook_leads_account_id">Account </label>
                                        <select class="form-control" id="facebook_leads_account_id"
                                            name="facebook_leads_account_id">
                                            <option>Select</option>
                                            @foreach ($ad_accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    @if ($account->id == $facebook_lead->account_id) {{ 'selected' }} @endif>
                                                    {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="facebook_leads_country">Campagin Country </label>
                                        <select class="form-control" id="facebook_leads_country"
                                            name="facebook_leads_country">
                                            <option>Select</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name }}"
                                                    @if ($country->name == $facebook_lead->campaign_country) {{ 'selected' }} @endif>
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 float-end">
                                        <input type="submit" class="btn btn-success" value="Submit" />
                                    </div>
                                    <div class="form-group mb-3 formoutput">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <table class="table  table-bordered">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Name</th>
                                            <th width="20px">Token</th>
                                            <th>Created At</th>
                                            <th>Expired At</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ad_accounts as $ad_account)
                                            <tr>
                                                <td>{{ $ad_account->name }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-xs" type="button"
                                                        data-toggle="collapse" data-target="#contentId{{ $ad_account->id }}"
                                                        aria-expanded="false" aria-controls="contentId">
                                                        Show
                                                    </button>
                                                    </p>
                                                    <div class="collapse" id="contentId{{ $ad_account->id }}">
                                                        {{ $ad_account->token }}
                                                    </div>
                                                </td>
                                                <td>{{ $ad_account->updated_at }}</td>
                                                <td>{{ date('Y-m-d H:i:s', strtotime($ad_account->updated_at . '+90 days')) }}
                                                </td>
                                                <td>
                                                    @if (date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime($ad_account->updated_at . '+90 days')))
                                                        Active
                                                    @else
                                                        <span class="text-danger">In-Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
    <p>
    @endsection
    @section('custom-scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $(document).on('submit', '#fbchannelform', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                        success: function(formResp) {
                            let htmlalert = '';
                            if (formResp.type == true) {
                                htmlalert = `<div class="alert alert-success" role="alert">
                                <strong>Success</strong> Record Addedd!!!
                            </div>`;
                                $('#fbchannelform')[0].reset();
                            } else {
                                htmlalert = `<div class="alert alert-danger" role="alert">
                                <strong>Success</strong> Record Addedd!!!
                            </div>`;
                            }
                            $('.formoutput').html(htmlalert);
                            window.setTimeout(() => {
                                $('.formoutput').html('');
                            }, 2000);
                        }
                    });
                });
            });
        </script>
    @endsection

@section('page-title', 'Double Approval')
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
                    <h4 class="page-title">Photo Pending !</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Approval</a></li>
                            <li class="breadcrumb-item active">Photo_Pending</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <table class="col-md-12 user_info">
                    </table>
                    <div class="col-md-12 output_message"></div>
                    <div class="col-md-6 profile_pic">
                    </div>
                    <div class="col-md-6">
                        <div class="row other_pic">
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body" style="padding: 0.2rem">
                                        <h4>Un Approved Photos : <span class="unapprovedPhotos"></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- end col-->
            </div>
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


        .user_info th {
            background-color: black;
            color: white
        }
    </style>

@endsection
@section('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
        integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/2.0.0-alpha.2/cropper.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /**
             * keep image id (purana wal ahatana nahi hai jatbak new approve nahi ho)
             */

            loadUserImages();

            function loadUserImages() {
                $('.loader').show();
                const noImage = "{{ config('constants.no_image')[0] }}";
                let photoHtml = "";
                let unApproveCount = 0;
                $.ajax({
                    url: "{{ route('getuserunapprovedphotos') }}",
                    type: "get",
                    success: function(userPicResp) {
                        $('.loader').hide();
                        photoHtml += '<div class="row">';
                        profilePicHtml = "";
                        otherPicHtml = "";
                        userData =
                            `<tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Birth Date</th>
                                <th>Gender</th>

                            </tr>
                            <tr>
                                <td>${userPicResp.user_data.name}</td>
                                <td>${userPicResp.user_data.user_mobile}</td>
                                <td>${userPicResp.user_data.birth_date.split(' ')[0]}</td>
                                <td>${userPicResp.user_data.gender}</td>
                            </tr>
                             `;
                        $('.user_info').html(userData)
                        for (let j = 0; j < userPicResp.user_photos.length; j++) {
                            const userPhotos = userPicResp.user_photos[j];
                            let replacementOf = userPhotos.replacement_of;
                            if (userPhotos.profile_pic == 1) {
                                profilePicHtml = `
                                                <div class="card">
                                                    <img class="card-img-top w-100" src="https://hansmatrimony.s3.ap-south-1.amazonaws.com/uploads/${userPhotos.photo_url}" alt="Card image">
                                                    <div class="card-body">
                                                        <h4 class="card-title">${userPicResp.user_data.name}</h4>
                                                        <p class="card-text">${userPicResp.user_data.user_mobile}</p>`;
                                if (replacementOf != null && replacementOf > 0) {
                                    profilePicHtml +=
                                        `<a href="#" class="btn btn-danger see_replacement" photo_id="${replacementOf}">See Previous</a>`;
                                }
                                if (userPhotos.photo_status == 'pending') {
                                    profilePicHtml +=
                                        `
                                                        <a href="#" class="btn btn-danger reject_btm" photo_id="${userPhotos.id}" action_type="rejected" replacement_of="${replacementOf}">Reject</a>
                                                        <a href="#" class="btn btn-success float-end btn-approve" photo_id="${userPhotos.id}" action_type="active" replacement_of="${replacementOf}">Approve</a>`;
                                }
                                profilePicHtml += `</div>
                                                </div>`;
                                $('.profile_pic').html(profilePicHtml);
                            } else if (userPhotos.photo_status == 'pending') {
                                unApproveCount++;
                                otherPicHtml += `<div class="col-md-12"><div class="card image_div_no${unApproveCount}">
                                                    <img class="card-img-top" src="https://hansmatrimony.s3.ap-south-1.amazonaws.com/uploads/${userPhotos.photo_url}" alt="Card image">
                                                    <div class="card-body">`;
                                if (replacementOf != null && replacementOf > 0) {
                                    otherPicHtml +=
                                        `<a href="#" class="btn btn-danger see_replacement" photo_id="${replacementOf}">See Previous</a>`;
                                }
                                otherPicHtml += `<a href="#" class="btn btn-danger reject_btm" div_no="image_div_no${unApproveCount}" photo_id="${userPhotos.id}" action_type="rejected" replacement_of="${replacementOf}">Reject</a>
                                                        <a href="#" class="btn btn-success float-end approve_btm" div_no="image_div_no${unApproveCount}" photo_id="${userPhotos.id}" action_type="active" replacement_of="${replacementOf}">Approve</a>
                                                    </div>
                                                </div></div>`;
                            }
                        }
                        photoHtml += '</div>';
                        $('.other_pic').html(otherPicHtml);
                        $('.unapprovedPhotos').text(unApproveCount);
                    }
                })
            }

            $(document).on('click', '.reject_btm', function(e) {
                e.preventDefault();
                $('.loader').show();
                let imageId = $(this).attr('photo_id');
                let actionType = $(this).attr('action_type');
                let replacementOf = $(this).attr('replacement_of');
                let divNo = $(this).attr('div_no');
                let actionOnPic = takeActionOnPics(imageId, actionType, replacementOf, divNo);
            })

            $(document).on('click', '.approve_btm', function(e) {
                e.preventDefault();
                $('.loader').show();
                let imageId = $(this).attr('photo_id');
                let actionType = $(this).attr('action_type');
                let replacementOf = $(this).attr('replacement_of');
                let divNo = $(this).attr('div_no');
                let actionOnPic = takeActionOnPics(imageId, actionType, replacementOf, divNo);
            })

            $(document).on('click', '.btn-approve', function(e) {
                e.preventDefault();
                $('.loader').show();
                $('.loader').show();
                let imageId = $(this).attr('photo_id');
                let actionType = $(this).attr('action_type');
                let replacementOf = $(this).attr('replacement_of');
                let divNo = null;
                takeActionOnPics(imageId, actionType, replacementOf, divNo)
            });

            $(document).on('click', '.btn-reject', function(e) {
                e.preventDefault();
                $('.loader').show();
                let userId = $(this).attr('userId');
                let imageIndex = $(this).attr('iamgeIndex');
                let actionType = "rejected";
                let oldImage = $(this).attr('oldImage');
                let newImage = $(this).attr('newImage');
                takeActionOnPics(userId, imageIndex, actionType, oldImage, newImage);
            });

            function takeActionOnPics(imageId, actionType, replacementOf, divNo) {
                $.ajax({
                    url: "{{ route('takeactiononpics') }}",
                    type: "post",
                    data: {
                        image_index: imageId,
                        action_type: actionType,
                        replacement_of: replacementOf,
                    },
                    success: function(picResp) {
                        // return picResp;
                        $('.loader').hide();
                        let messageHtml = "";
                        let aunApprovedPiccount = $('.unapprovedPhotos').text();
                        if (picResp.type == true) {
                            $('.' + divNo).remove();
                            let newCount = parseInt(aunApprovedPiccount) - 1;
                            if (newCount == 0) {
                                loadUserImages();
                            }

                            $('.unapprovedPhotos').text(newCount);
                            messageHtml = `<div class="alert alert-success" role="alert">
                                    <strong>Success ! </strong> ${picResp.message}
                                </div>`;

                        } else {
                            messageHtml = `<div class="alert alert-danger" role="alert">
                                    <strong>Alert ! </strong> ${picResp.message}
                                </div>`;
                        }
                        $('.output_message').html(messageHtml);
                    }
                });
            }
        });
    </script>
@endsection

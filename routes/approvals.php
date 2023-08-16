<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\UserPhotoController;
use App\Http\Controllers\PsDashboardController;
use App\Http\Controllers\saveApproValController;

// approval routes

/*
The process of approving profiles involves reviewing the information provided by users who have applied for approval. This includes verifying their identity, checking their qualifications and experience, and assessing their suitability for the platform. In cases where a user's profile is not approved, the reasons for the rejection are clearly communicated to the user. The user is then given the opportunity to provide additional information or make changes to their profile before resubmitting it for review. The goal is to ensure that only qualified and trustworthy users are approved to participate on the platform, which helps to maintain a high level of quality and safety for all users.
*/

Route::get('approve-user-profile', [UserDataController::class, 'approveUserDataProfiles'])->name('approveprofile');
Route::get('approve-user-pending-profile', [UserDataController::class, 'approveUserDataPendingProfiles'])->name('approvependingprofile');
Route::get('approve-user-double-profile', [UserDataController::class, 'approveUserDataDoubleProfiles'])->name('approvedoubleprofile');
/*
 Once you have reviewed the user's uploaded photos and determined that they meet the necessary criteria, you can proceed to approve them. This involves carefully analyzing each photo, ensuring that it meets the requirements of the platform and the intended purpose of the photos. Additionally, it may be necessary to provide feedback or suggestions to the user in order to help them improve the quality of their photos in the future. By taking the time to thoroughly evaluate and approve user's photos, you can ensure that your platform maintains a high standard of content and user satisfaction.
*/
Route::get('approve-user-photo-profile', [UserDataController::class, 'approveUserDataPhotoProfiles'])->name('approvephotoprofile');
Route::get('getmessagecounts', [UserDataController::class, 'countMessages'])->name('getmessagecounts');
Route::post('send-message-all', [UserDataController::class, 'sendWhatsAppMessageCommon'])->name('sendmessageall');
/*
To retrieve unapproved profiles from a system, you can specify date ranges to filter the profiles. This process allows you to focus on specific time periods and identify unapproved profiles that may have been missed during previous reviews. Additionally, you can filter profiles by message content, which allows you to identify profiles that may contain inappropriate language or content. By conducting a thorough review of unapproved profiles, you can ensure that your system remains safe and appropriate for all users.
*/
Route::get('day-range-wise-data', [UserDataController::class, 'dayRangeWiseData'])->name('dayrangewisedata');
Route::get('pending-profile-data', [UserDataController::class, 'profileDataApproval'])->name('pendingprofiledata');
Route::post('approve-user-profile', [UserDataController::class, 'approveUserProfile'])->name('approveuserprofile');
Route::get('reject-profile-during-approve', [UserDataController::class, 'rejectUserProfile'])->name('rejectuserprofile');
Route::get('markMarrieduserprofile-profile-during-approve', [UserDataController::class, 'markMarrieduserprofile'])->name('markMarrieduserprofile');
Route::get('rejected-profiles', [UserDataController::class, 'rejectedProfiles'])->name('rejectedprofiles');
Route::get('rejected-profiles-list', [UserDataController::class, 'getRejectedProfiles'])->name('rejectedprofilesdata');
Route::get('approved-profiles', [UserDataController::class, 'approvedProfiled'])->name('approvedprofiles');
Route::get('get-approved-profiles', [UserDataController::class, 'getApprovedProfiles'])->name('getapprovedprofiles');
Route::get('double-approval-profiles', [UserDataController::class, 'getDoubleApproveProfile'])->name('doubleapproveprofile');
Route::post('double-approval-profiles', [UserDataController::class, 'doubleApproveProfile'])->name('getdoubleapproveprofile');
Route::get('un-approved-photos', [UserDataController::class, 'getUnapprovedPhotos'])->name('getunapprovedphotos');
Route::post('save-profile-list-send', [PsDashboardController::class, 'saveSendProfileList'])->name('saveprofilelistsend');
Route::get('send-profile-list', [PsDashboardController::class, 'sendProfileListUser'])->name('sendprofilelist');
Route::get('send-whatsapp-message', [UserDataController::class, 'sendWhatsAppMessageCommon'])->name('sendWhatsAppMessageCommon');
Route::get('client-call-interaction', [UserDataController::class, 'viewCallInteractPage'])->name('clientcallinteract');
Route::get('get-welcome-call-profiles', [UserDataController::class, 'getWelcomeCallData'])->name('getwelcomecallprofiles');
Route::get('get-verification-call-profiles', [UserDataController::class, 'getVerificationCallData'])->name('getverificationcallprofiles');
Route::post('mark-welcome-done', [UserDataController::class, 'markWelcomeDone'])->name('markwelcomedone');
Route::post('mark-verification-done', [UserDataController::class, 'markVerificationDone'])->name('markverificationdone');
Route::get('get-unapproved-photo-with-details', [UserPhotoController::class, 'getUSerPics'])->name('getuserunapprovedphotos');
Route::post("take-action-on-pics", [UserPhotoController::class, 'actionOnImages'])->name('takeactiononpics');
Route::get('incomplete-leads-pending', [UserDataController::class, 'incompleteLeadsPending'])->name('incompleteleadspending');
Route::get('incomplete-leads-pending-list', [UserDataController::class, 'incompleteLeadsPendingList'])->name('incompleteleadspendinglist');
Route::post('approval-save-personal', [saveApproValController::class, 'approvalSavePersonal'])->name('approvel-save-personal');
Route::post('approvel-save-professional', [saveApproValController::class, 'approvalSaveProfessional'])->name('approvel-save-professional');
Route::post('approvel-save-family', [saveApproValController::class, 'approvalSaveFamily'])->name('approvel-save-family');
Route::post('approvel-save-photo', [saveApproValController::class, 'approvalSavePhoto'])->name('approvel-save-photo');
Route::post('approvel-save-userpreferences', [saveApproValController::class, 'approvalSaveUserPreferences'])->name('approvel-save-userpreferences');
Route::post('approvel-save-userProfileUpload', [saveApproValController::class, 'uploadUserImage'])->name('approvel-save-userProfileUpload');
Route::post('aprove-by-me', [UserDataController::class, 'totalAprove'])->name('aprove-by-me');
Route::post('saveincompleteleads', [saveApproValController::class, 'saveincompleteleads'])->name('saveincompleteleads');

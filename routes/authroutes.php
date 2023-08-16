<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KundliController;
use App\Http\Controllers\TeamLeaderController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\PsDashboardController;
use App\Http\Controllers\UserController;
/*
            After successfully logging in, you will be automatically directed to the dashboard. The dashboard is where you can access a variety of tools and resources to manage your account and make the most of our services. From the dashboard, you can view your account balance, review your transaction history, and manage your payment methods. You can also access our customer support team, who are available to assist you with any questions or concerns you may have. In addition, the dashboard provides important notifications and alerts regarding your account, so you can stay up-to-date on any changes or updates to our services.
    */

Route::get('user-dashboard', [DashboardController::class, 'userDashboard'])->name('userdashboard');

/*
            Admin protected routes defined below
            To access these routes, users are required to have appropriate permissions and credentials. The purpose of these protected routes is to ensure the security and integrity of the system, preventing unauthorized access and malicious actions. By restricting access to only authorized personnel, the system is better able to maintain confidentiality, availability, and reliability. In addition, the administrator-protected routes allow for more granular control and delegation of responsibilities, improving the overall efficiency and effectiveness of the system
    */


/*
The telesales dashboard is a centralized location where sales representatives can manage their leads and follow up with potential clients. With access to all the necessary information in one place, sales representatives can easily prioritize leads and focus on those that are most likely to convert into customers. In addition, the dashboard provides valuable insights into the performance of the sales team, allowing managers to identify areas for improvement and make data-driven decisions. Overall, the telesales dashboard is an essential tool for any company looking to streamline their sales process and improve their bottom line.*/
// checkin
Route::prefix('user-checkin')->group(function () {
        Route::get("checkin", [CheckInController::class, 'index'])->name('checkin');
        Route::get("markin", [CheckInController::class, 'markCheckedIn'])->name('markcheckin');
        Route::get("markout", [CheckInController::class, 'markCheckedOut'])->name('markcheckout');
});

// Teamleader Routes
Route::prefix('teamleader')->group(function () {
        /*
    When working in telesales, transferring leads to other team members can be a great way to increase efficiency and improve sales. By dividing up the workload, each team member can focus on a specific set of leads, which can lead to better engagement and increased sales. Additionally, transferring leads can help ensure that each lead is being followed up on in a timely and consistent manner, leading to a more positive customer experience. However, it's important to make sure that the transfer process is streamlined and well-communicated to avoid any confusion or delays. Regular team meetings and clear guidelines for lead transfer can help ensure that the process runs smoothly and effectively. Overall, while transferring leads may require some extra effort upfront, the benefits in terms of increased productivity and sales can be well worth it in the long run.
    */
        Route::get('lead-transfer-view', [TeamLeaderController::class, 'index'])->name('transferleads');
        Route::get('accessable-team-leaders', [TeamLeaderController::class, 'getAccessableTemples'])->name('accessabletempleides');
        Route::get('count-temple-leads', [TeamLeaderController::class, 'getCountOfLeads'])->name('counttempleleads');
        Route::post('transfer-lead-to-other', [TeamLeaderController::class, 'transferLeads'])->name('savetransferleads');
});



/*
     Our company's personalized dashboard is designed to cater to the needs of all relationship managers. With our state-of-the-art dashboard, managers can easily track and manage client relationships, monitor sales performance, and keep track of important tasks and deadlines. The dashboard is user-friendly and customizable to fit the unique needs of each manager. Additionally, we offer a comprehensive training program to ensure that managers can fully utilize all the features and benefits of our dashboard. With our cutting-edge technology and exceptional customer support, our dashboard is a perfect solution for relationship managers who are looking to streamline their workflow and improve their performance.
    */
Route::prefix('personalized-dashboard')->group(function () {
        /*
             As I review the list of all profiles assigned to me, I am struck by the sheer variety of the individuals represented. Each profile tells a unique story, with its own set of strengths, weaknesses, and areas for growth. As I consider each profile in turn, I am reminded of the importance of taking a personalized approach to mentorship and support, tailoring my guidance to each individual's specific needs and goals. I am grateful for the opportunity to work with such a diverse and talented group of individuals, and look forward to the insights and growth that will undoubtedly come from this experience.
            */
        Route::get('database', [PsDashboardController::class, 'index'])->name('database');
        Route::get('mine-user-datalist', [PsDashboardController::class, 'myUserList'])->name('myuserlist');
        Route::get('mine-user-datalist-pending', [PsDashboardController::class, 'myUserListpending'])->name('myUserListpending');

        Route::post('update-user-validity', [UserPreferenceController::class, 'updateUserValidity'])->name('updateuservalidity');
        /*
            The system will operate on the basis of matching profiles with each other according to their preferences. This will be done by analyzing the data provided in each profile, taking into account the various parameters that are relevant to the matching process. For example, the system will consider factors such as the user's age, gender, location, interests, and hobbies, among others. Additionally, users will have the ability to customize their preferences according to their own specific needs and requirements, ensuring that they are matched with like-minded individuals who share similar interests and values.
            */
        Route::get('find-match', [PsDashboardController::class, 'findMatchView'])->name('findmatch');
        /*
             One potential revision to consider would be to expand on the details of the process for updating profiles. This could include information on the frequency with which profiles are updated, the specific day on which profiles are sent, and the criteria used to determine which profiles are selected for sending. Additionally, it may be useful to provide more context on the purpose of sending profiles on a particular day, such as how it fits into the broader goals and objectives of the organization. Another option would be to discuss the various stakeholders involved in the profile update process, and their respective roles and responsibilities. By including these additional details and insights, we can provide a more comprehensive and informative document for the intended audience.
            */
        Route::post('update-profile-sent-day', [PsDashboardController::class, 'profileSentDay'])->name('updtepropfilesentday');
        Route::get('profile-lists-pdf', [PsDashboardController::class, 'displayProfilePdfs'])->name('pdfprofiles');
        Route::get('profiles-lists-pdf', [PsDashboardController::class, 'displayProfilesPdfs'])->name('pdfprofiles');
        Route::get('sample-lists-pdf', [PsDashboardController::class, 'displaySampleProfilesPdfs'])->name('samplepdfprofiles');
        /*
            In order to improve the user experience, we may need to provide more detailed information related to the user's action of choice. For instance, when a user's action is "rejected," we could provide a more detailed message explaining why the user's choice was rejected. Similarly, when a user's action is "selected," we could provide more information on what happens next in the user's journey. Additionally, when a user's action is "shortlisted," we could provide more information on the next steps in the selection process. By providing more information, we can help to ensure that users have a clear understanding of their status and what they can expect going forward.
            */
        Route::get('update-user-action', [PsDashboardController::class, 'updateUserAction'])->name('updateuseraction');
        Route::get('get-contacted-user-list', [PsDashboardController::class, 'getContactedUserList'])->name('getcontacteduserlist');
        Route::post('update-premium-meeting', [PsDashboardController::class, 'updatecontactedStatus'])->name('updatepremiummeetingstatus');
        Route::get('get-all-preimum-meeting-list', [PsDashboardController::class, 'getAllPremiumLkedProfile'])->name('getallpreimumeetinglist');
        /*
            As per our records, a list of profiles has been sent to the users in the past. It is important to keep the users engaged by providing them with regular updates. Therefore, we suggest sending the list of profiles on a daily basis. Additionally, it might be helpful to include some details about each profile, such as their experience, skills, and interests. This will give users a better understanding of the profiles and help them make informed decisions. We recommend adding a brief summary at the end of each day's list to provide some context and encourage users to continue engaging with the service.
            */
        Route::get('day-wise-profile-sent', [PsDashboardController::class, 'dayWiseProfileStndList'])->name('daywiseprofilesent');
        Route::get('send-profile-list-user-matches', [PsDashboardController::class, 'sentListUserMatch'])->name('sentprofilefromusermatch');
        Route::get('overall-yes-pending', [PsDashboardController::class, 'overallYesPending'])->name('yespending');
        Route::get('today-sent-list', [PsDashboardController::class, 'todaySentList'])->name('todaysentlist');
        /*
                    The following is a list of users who have not had their weekly reports sent out for their profiles. This list includes individuals who may have had technical difficulties or who may have been inadvertently overlooked in the distribution process. It is important to ensure that all users receive the necessary information in a timely manner, and steps will be taken to rectify any issues that may have arisen in the distribution of these reports. Furthermore, we will be implementing new procedures to ensure that all users are included in the regular distribution of their weekly reports going forward.
            */
        Route::get('weekly-profile-not-sent', [PsDashboardController::class, 'weeklyProfileNotSent'])->name('weeklyprofilenotsent');
        Route::get('weekly-profile-not-sent-data', [PsDashboardController::class, 'weeklyProfileNotSentData'])->name('weeklyprofilenotsentdata');
        /*
                    One of the most useful features of a notes app is the ability to save important information for future reference. Whether it's jotting down ideas, recording meeting minutes, or simply keeping track of daily tasks, notes can provide a convenient way to store and organize information. In fact, by using notes to keep track of important things, you can easily recall details that might otherwise be forgotten. Additionally, having notes as a reference can help you to stay on top of important deadlines, stay organized, and even increase productivity. Therefore, it's essential to have a reliable notes app that can help you to keep track of all your important information in one place.
            */
        Route::post('save-user-notes', [UserPreferenceController::class, 'saveUserNotes'])->name('saveusernotes');
        Route::get('overall-yes-meeting', [PsDashboardController::class, 'overallYesMeeting'])->name('overallyesmeeting');
        Route::get('overall-yes-meeting-list', [PsDashboardController::class, 'loadAllPremiumMeetingList'])->name('overallyesmeetinglist');
        Route::get("get-transfer-profile-view", [PsDashboardController::class, "transferProfileView"])->name('transferprofile');
        Route::get('get-templewise-user-profile-list', [PsDashboardController::class, "transferProfileData"])->name('gettempleprofiles');
        Route::post('transfer-lead-to-temple', [PsDashboardController::class, "transferProfiles"])->name('transferleadtoteple');
        /*
                    An issue that needs to be addressed is the overall pending list of users whose actions are not being taken on their behalf. This list could include users who have reported issues or submitted requests for assistance, but have not received any response or resolution from the company. It is important to review this list regularly and ensure that all users receive appropriate attention and support. In addition, it may be beneficial to implement a system to track and prioritize these pending requests in order to ensure timely and effective resolution. By taking proactive steps to address these pending requests, the company can improve overall customer satisfaction and loyalty.
            */
        Route::get('overall-pending-list', [PsDashboardController::class, 'overallPendingData'])->name('overallpendinglist');
        Route::get('over-all-pending-user-list', [PsDashboardController::class, 'overAllPendingListView'])->name('overalluserpendinglist');
        /*
                    Based on the text, it seems like there is a list of users who have yet to respond with a "yes" or "no". It would be helpful to follow up with these users to ensure that their responses are properly recorded. Additionally, it may be worth considering sending out reminders or offering additional incentives to encourage them to respond. Ultimately, having a complete and accurate list of responses will be crucial for making informed decisions moving forward.
            */
        Route::get('overall-yes-pending-list', [PsDashboardController::class, 'overallYesPendingData'])->name('yespendinglist');
        Route::get('over-all-yes-pending-user-list', [PsDashboardController::class, 'overAllYesPendingListView'])->name('overallyesuserpendinglist');
        Route::get("get-overall-yes-pending-user-data", [PsDashboardController::class, 'overAllTesMeetingList'])->name("getoverallyespendinguserdata");
        Route::get("history-details", [PsDashboardController::class, 'historyDetails'])->name("historydetails");
        Route::post('save-basic-profile', [PsDashboardController::class, 'saveBasicProfile'])->name('savebasicprofile');
});

// customer care routes
Route::prefix('custmer-care')->group(function () {
        Route::get('open-tickets', [ComplainController::class, 'index'])->name('opentickets');
        Route::get('get-all-open-tickets', [ComplainController::class, 'getOpenTicekets'])->name('getallopentickets');
        Route::get('get-all-ticket-counts', [ComplainController::class, 'getAllTiceketCount'])->name('getallticketcounts');
        Route::get('get-my-ticket-counts', [ComplainController::class, 'getMyTiceketCount'])->name('getmyticketcounts');
        Route::post('resolve-ticket', [ComplainController::class, 'resolveTicket'])->name('resolveticket');
        Route::get('assign-open-ticket-to-me', [ComplainController::class, 'assignOpenTicket'])->name('assignopenticket');
        Route::get('my-open-tickets', [ComplainController::class, 'myOpenTicketView'])->name('myopenticket');
        Route::get('get-my-open-tickets', [ComplainController::class, 'getMyOpenTickets'])->name('getmyopentickets');
        Route::post('update-ticket-status', [ComplainController::class, '/858jyfxxicketStatus'])->name('updateticket');
        Route::post('add-new-ticket', [ComplainController::class, 'createNewTicket'])->name('addnewticket');
        Route::get('serach-by-mobile', [UserDataController::class, 'userByMobile'])->name('serachbymobile');
        Route::get('serach-by-mobile-mydb', [UserDataController::class, 'userByMobile'])->name('serachbymobileydb');
        Route::get('all-record-group-wise', [ComplainController::class, 'allRecordData'])->name('detailedview');
        Route::get('all-record-group-wise-data', [ComplainController::class, 'allRecordGetData'])->name('detailedviewdata');
        Route::get('get-user-all-tickets', [ComplainController::class, 'userTicketsByMobile'])->name('getallusertickets');
        Route::get('get-all-complain-category', [ComplainController::class, 'getComplainCategory'])->name('getcomplaincategory');
});

// common routes for all users
Route::prefix('common-routes')->group(function () {
        Route::get('sample-profiles', [PsDashboardController::class, 'sampleProfile'])->name('sampleprofile');
        Route::get('sample-profiles-data', [PsDashboardController::class, 'getSampleFilteredProfiles'])->name('sendsampleprofile');
        Route::post('delete-user-pic', [UserDataController::class, 'deleteUSerPic'])->name('deleteuserpic');
        Route::post('save-user-credits', [UserDataController::class, 'addUserCredit'])->name('addusercredit');
        Route::get('all-rm-list', [UserController::class, 'getAllRmList'])->name('allrmlist');
        Route::get('my-database-crm', [UserDataController::class, 'myCrmDatabse'])->name('mydatabsecrm');
        Route::get('my-database-pending', [UserDataController::class, 'myCrmDatabsepending'])->name('mydatabsepending');
        Route::get('count-user-data', [DashboardController::class, 'counteUserData'])->name('counteuserdata');
        Route::get('over-all-search', [UserDataController::class, 'overAllSearch'])->name('overallsearch');
});

Route::prefix('other-stuffs')->group(function () {
        /*
                    This document describes the process of experiencing a user dashboard. When the user logs in, they will be prompted to verify their OTP (one-time password) for security purposes. Once the OTP has been verified, the user will be able to access their dashboard, which provides a range of features and information. For example, the dashboard may display the user's recent activity, such as their most recent purchases or interactions with other users. It may also include tools for managing the user's account, such as changing their password or updating their personal information. Overall, the user dashboard is a powerful tool that enables users to engage with the platform in a more meaningful way.
            */
        Route::get('login-others-account', [UserController::class, 'loginOtherView'])->name('loginotersaccount');
        Route::get('get-all-users', [UserController::class, 'index'])->name('getallusers');
        Route::get('login-other-account', [UserController::class, 'loginOtherAccount'])->name('loginotheraccount');
        /*
            When it comes to managing a database related to Facebook, there are a number of factors that come into play. One important consideration is how to effectively query the database to obtain the desired information. This can involve utilizing various search algorithms and database management techniques to ensure that the query results are accurate and relevant.
            Additionally, it is important to consider the size and complexity of the database in question. As the amount of data contained within the database grows, it becomes increasingly difficult to manage and organize the information in a way that is both efficient and effective. This can require the use of specialized tools and software designed specifically for large-scale database management.
            Overall, effective Facebook database management requires a combination of technical knowledge, strategic planning, and a deep understanding of the underlying database architecture. By implementing best practices and staying up-to-date with the latest trends and technologies, businesses can ensure that their Facebook databases are well-managed and optimized for success.
            */
        Route::get('manage-category-relations', [CategoryController::class, 'index'])->name('managecategory');
        Route::get('get-relation-categories', [CategoryController::class, 'getRelationCategory'])->name('getrelationcats');
        Route::post('save-relation-categories', [CategoryController::class, 'saveRelationCategory'])->name('savetrelationcats');
        Route::get('delete-relation-categories', [CategoryController::class, 'deleteRelationCategory'])->name('deleterelcat');

        Route::get('all-temple-relation', [CategoryController::class, 'allTempleRelation'])->name('alltemplerelation');
        Route::post('save-assign-relation-cats', [CategoryController::class, 'saveAssignRelation'])->name('saveassigntrelationcats');
        Route::get('delete-assigned-relation', [CategoryController::class, 'deleteRelaion'])->name('deleteassignedrelation');
        Route::get('get-facebook-query', [CategoryController::class, 'getFacebookQueryData'])->name('getfacebookquery');
        Route::get('get-website-query', [CategoryController::class, 'getWebsiteQueryData'])->name('getwebsitequery');

        Route::post('edit-facebook-query', [CategoryController::class, 'editFacebookQuery'])->name('editfacebookquery');
        Route::post('edit-website-query', [CategoryController::class, 'editWebQuery'])->name('editwebquery');

        Route::post('save-user-details', [UserController::class, 'saveUserDetails'])->name('saveuserdetails');

        Route::get('manage-team-leader', [TeamLeaderController::class, 'manageTeamLeader'])->name('manageteamleader');
        Route::get('list-team-leader', [TeamLeaderController::class, 'leadTeamLeaders'])->name('listteamleader');
        Route::get('get-team-leader-team', [TeamLeaderController::class, 'getTeamList'])->name('getteamlists');
        Route::post('manage-team-leader-list', [TeamLeaderController::class, 'manageTeamleraderList'])->name('updateteamleaderlist');
});

// hr dashboard routes
Route::prefix('hrms')->group(function () {
        Route::get('emplouyees-page', [UserController::class, 'getEmployeesPage'])->name('employeespage');
        Route::get('employee-attendanec', [AttendanceController::class, 'index'])->name('employeesattpage');
        Route::get('get-att-report', [AttendanceController::class, 'getAttendanceReport'])->name('getattreport');
        Route::get('get-detailed-att-report', [AttendanceController::class, 'getDetailedAttendanceReport'])->name('getattdetailedreport');
        Route::get('detailed-atendance-data', [AttendanceController::class, 'getDetailedAttendanceData'])->name('detailedatendancedata');
});

// mislenious routes
Route::get('get-user-data-by-id', [UserDataController::class, 'getUserDataById'])->name('getuserdatabyid');
Route::post('upload-user-image', [UserDataController::class, 'uploadUserImage'])->name('uploaduserimage');
Route::get('my-database', [UserController::class, 'myDatabase'])->name('mydatabase');
Route::post('update-user-profile', [UserDataController::class, 'updateUserProfile'])->name('updateuserprofile');
Route::get('get-filtered-profiles', [PsDashboardController::class, 'getFilteredProfiles'])->name('getfilteredprofiles');
Route::get('match-kundli-score', [KundliController::class, 'matchProfileKundli'])->name('matchkundliscore');
Route::post("save-facebbook-token", [ChannelController::class, 'saveFbToken'])->name('savefbtoken');
Route::get('mark-profile-married', [UserDataController::class, 'markMarried'])->name('markprofilemarried');
Route::get('mark-profile-premium', [UserDataController::class, 'markPremium'])->name('markprofilepremium');

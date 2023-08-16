<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddLeadDirectly;
use App\Http\Controllers\databaseMigrationController;
use App\Http\Controllers\FormDataController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\PsDashboardController;
use App\Http\Controllers\ReligionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
This link will redirect you to the main page. On the main page, you will find a variety of options to choose from, including links to related pages, recent updates, and featured content. Additionally, you can use the search bar to find specific information or navigate through the site using the menu bar at the top of the page. It is important to note that the main page is a hub for all of the content on the website, so taking the time to explore it thoroughly can be very useful in finding what you are looking for.
*/

// Route::get('/dbmigrate', [databaseMigrationController::class, 'index']);
// Route::get('/dbupdate', [databaseMigrationController::class, 'migrateDb'])->name('dbupdate');
// Route::get('/dbupdate', [databaseMigrationController::class, 'update'])->name('dbupdate');
Route::get('user-profile-lists-pdf', [PsDashboardController::class, 'displayProfilePdfs']);
Route::get('/', function () {
        return view('welcome');
})->name('welcome');
// Route::get('hash', [DashboardController::class, 'hashmake']);

/*
Middleware is an essential component of any system that requires secure data management. It is used to prevent unauthorized access to sensitive data by acting as a barrier between the data and any outside request. By using middleware, access to data can be regulated and monitored, ensuring that only authorized users can access the data they need. In addition to its security benefits, middleware can also help to improve the efficiency of a system by providing a layer of abstraction between the application and the underlying operating system. This can help to simplify the development process and make it easier to maintain and update the system over time.
*/

Route::get('/swagger', function () {
        return view('swagger');
});

// Open Routes for common data like dropdown and all that starts
Route::get('all-religion', [ReligionController::class, 'getAllReligion'])->name('allreligion');
Route::get('get-all-educations', [FormDataController::class, 'getAllEducations'])->name('getalleducations');
Route::get('get-all-cities', [FormDataController::class, 'getAllCities'])->name('getallcities');
Route::get('get-all-castes', [FormDataController::class, 'getAllCastes'])->name('getallcastes');
Route::get('get-relation', [NodeController::class, 'relationMapping'])->name('getrelation');
Route::get('get-occupation', [NodeController::class, 'occupationList'])->name('getoccupation');
Route::get('get-parent-occupation', [NodeController::class, 'parentOccupations'])->name('getparentoccupation');
Route::get('get-manglik-status', [NodeController::class, 'manglikStatus'])->name('getmanglikstatus');
Route::get('get-marital-status', [NodeController::class, 'maritalStatus'])->name('getmaritalstatus');
Route::get('get-qualification-by-id', [NodeController::class, 'qualificationById'])->name('getqualificationById');
Route::get('get-all-countries', [NodeController::class, 'getAllCountries'])->name('getallcountries');
Route::get('get-all-temples', [UserController::class, 'getAllTemples'])->name('getalltemples');
// Open Routes Ends


// admin login
Route::get('asrewgfgwerda89898asda', function () {
        return view('auth.login');
});


Route::get('indexpage', function () {
        return view('index');
});

Route::post('login-users', [UserController::class, 'loginUsersUsingOTP'])->name('loginusers');
Route::post('verify-user-otp', [UserController::class, 'verifyUserMobile'])->name('verifyuserotp');
Route::post('admin-login', [UserController::class, 'adminLogin'])->name('adminlogin');
Auth::routes([
        'register' => false, // Registration Routes...
        'reset' => false, // Password Reset Routes...
        'verify' => false, // Email Verification Routes...
        'login' => false, // Email Verification Routes...
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/cache-clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');
        echo "cache cleared";
});

Route::get('/update-user-data-id', [LeadController::class, 'upadteUserDataId']);
Route::get('add-lead-telesales-without-login', [AddLeadDirectly::class, 'index']);
Route::get('/update-user-data-by-id', [AddLeadDirectly::class, 'upadteUserDataById']);
Route::post('add-lead-telesales-without-login', [AddLeadDirectly::class, 'store']);

// migration routes
Route::get("populate-user-data", [MigrationController::class, 'getProfileDataAndSave']);
Route::get("populate-user-data-leads", [MigrationController::class, 'checkAndUpdateLeads']);
Route::get("populate-master-data", [MigrationController::class, 'prepareCaste']);
Route::get("populate-master-cities", [MigrationController::class, 'dispacthCities']);
Route::get("update-name", [MigrationController::class, 'updateName']);
Route::get("update-compatblities", [MigrationController::class, 'updateCompatblities']);
Route::get("update-preference", [MigrationController::class, 'updatePreference']);
Route::get("update-user-data-id", [MigrationController::class, 'updateUserDataId']);
Route::get('generate-profile-pdf', [PsDashboardController::class, 'displayProfilePdfs'])->name('pdfprofilessave');
Route::get('show-multiple-profiles', [PsDashboardController::class, 'CreateProfilePdfs'])->name('showprofilesingroup');
Route::get('show-multiple-profiless', [PsDashboardController::class, 'CreateProfilePdfss'])->name('showprofilesingroups');
Route::get('login-hans-users', [UserController::class, 'loginHansUsers']);

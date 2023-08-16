<?php

namespace App\Console;

use App\Representative;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Config;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CustomCommand::class,
        Commands\updateCompatibleTable::class,
        //Commands\RazorpayUpdate::class,
        //Commands\backup::class,
        Commands\resetProfileStatus::class,
        Commands\whatsapppoint::class,
        //Commands\JeevansathiToHans::class,
        //Commands\HansToMatchmakerz::class,
        //Commands\FacebookLeads::class,
        Commands\normalcalculation::class,
        //Commands\updateCheckIn::class,
        Commands\DailyCheck::class,
        //Commands\Database::class,
        Commands\ScheduleMessage::class,
        Commands\SendMessage::class,
        Commands\SendMessageAlternatively::class,
        Commands\SendMessageTwice::class,
        Commands\SendMessageWeekly::class,
        Commands\cofigClear::class,
        Commands\RecurringPayment::class,
        Commands\AssignLeadToWFH::class,
        Commands\MarkAssignNull::class,
        Commands\updateIsPremium::class,
        Commands\weeklyWhatsappPoint::class,
        //Commands\LastFiveDayBackup::class,
        // Commands\ProfileSentToProfileStatus::class,
        Commands\countProfResponse::class,
        Commands\attendanceAndPendnigPoints::class,
        Commands\sendNotifications::class,
        Commands\DeleteOTAC01::class,
        Commands\updateUserActivity::class,
        //Commands\updatePreferencesCal::class,
        Commands\verifyEmail::class,
        Commands\updateScore::class,
        Commands\storeOperatorLeads::class,
        Commands\resetLeadDetails::class,
        Commands\updateOnlineScore::class,
        Commands\dailyAppNotification::class,
        Commands\AppNotificatoinNotDAu::class,
        Commands\updateResponse::class,
        Commands\updateDAU::class,
        Commands\updateZgoodNessScore::class,
        Commands\sendWebPushNotification::class,
        //Commands\hansToMatchMaker::class,
        Commands\updateFirstLike::class,
        Commands\morningWebNotification::class,
        Commands\morningNotification::class,
        //Commands\afterNoonNotification::class,
        //Commands\eveningNotification::class,
        Commands\updateDailyActivity::class,
        Commands\sendVirtualLikes::class,
        Commands\sendAudioVirtualLikes::class,
        Commands\reassignleadtoonline::class,
        Commands\PaytmFailedTxn::class,
        Commands\DividePendingLeads::class,
        //Commands\transferProfilesToUserDetails::class,
        Commands\Migration\migrateLeadsToUserLeads::class,
        Commands\Migration\migratePreferencesToUserPreferences::class,
        Commands\Migration\migrateCompatibilitiesToUserCompatibilities::class,
        Commands\Migration\addDataInMatches::class,
        // Commands\Migration\addDataInMatches::class,
        Commands\resetDailyQuota::class,
        //Commands\matchmakerViewedMe::class,
        Commands\FecebookLeadsUpdate::class,

        // photo to photo url
        Commands\Migration\photoToPhotoUrl::class,
        Commands\Migration\generatePreference::class,
        //commands\updateUSerDataId::class,
        //commands\Migration\updateProfileSentDay::class,
        //commands\Migration\updatePremiumMeetings::class,
        //commands\Migration\updateProfileAndPreference::class,
        Commands\Migration\addDatafinalMigraionCron::class
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //set the timezone to IST to run commands at appropriate time

        $representatives = Representative::all();

        $checkin_time = [];
        $checkout_time = [];
        $i = 0;
        foreach ($representatives as $representative) {
            $checkin_time[$i] = date("H:i:s", $representative->check_in_time);
            $representative->w_hours = explode(':', $representative->w_hours);
            $hrs = $representative->w_hours[0];
            $minute = sizeof($representative->w_hours) > 1 ? $representative->w_hours[1] : 0;
            $checkout_time[$i] = date("H:i:s", $representative->check_in_time + $hrs * 60 * 60 + $minute * 60);
            $i++;
        }
        $min_max[0] = min($checkin_time);
        $min_max[1] = max($checkout_time);
        $schedule->command('daily:updateCompatibleTable')
            ->timezone('Asia/Kolkata')
            ->dailyAt('00:00');
        // $schedule->command('Reassign:LeadtoOnline')
        //     ->everyMinute();
        // $schedule->command('everyFiveMinutes:RazorpayUpdate')
        // ->everyFiveMinutes();

        $schedule->command('update:updateUSerDataId')
            ->everyThirtyMinutes();

        $schedule->command('facebook:leads')
            ->everyThirtyMinutes();
        //->everyMinute();

        $schedule->command('photo:phototophotourl')
            ->everyThirtyMinutes();
        $schedule->command('profile:updateProfileSentDay')
            ->everyThirtyMinutes();
        $schedule->command('update:updatePremiumMeeting')
            ->everyThirtyMinutes();
        $schedule->command('update:updateProfileAndPreference')
            ->everyThirtyMinutes();

        $schedule->command('preference:generatepreference')
            ->everyThirtyMinutes();

        $schedule->command('facebook:leadsupdate')
            ->everyThirtyMinutes();

        $schedule->command('assignto')
            ->everyThirtyMinutes();

        $schedule->command('update:checkin')
            ->everyFiveMinutes()
            ->between($min_max[0], $min_max[1]);

        $schedule->command('PaytmFailedTxn:Captured')
            ->dailyAt('08:00');

        $schedule->command('divide:pendingLeads')
            ->everyThirtyMinutes();

        /*** reset today's special daily quota at 06:00pm ***/
        $schedule->command('reset:dailyQuota')
            ->timezone('Asia/Kolkata')
            ->dailyAt('18:00');

        $schedule->command('reset:dailyQuota')
            ->timezone('Asia/Kolkata')
            ->dailyAt('13:00');

        // push profiles into matchmaker viewd me
        //$schedule->command('matchmaker:ViewedMe')->dailyAt('08:00');

        /*
        $schedule->command('sendMessage:daily')
        ->dailyAt('20:00')
        ->timezone('Asia/Kolkata')
        ->runInBackground();
        */

        // $schedule->command('sendMessage:alternate')
        // ->cron('0 20 */2 * *')
        // ->timezone('Asia/Kolkata')
        // ->runInBackground();

        // $schedule->command('sendMessage:twice')
        // ->cron('0 20 * * 2,5')
        // ->timezone('Asia/Kolkata')
        // ->runInBackground();

        // $schedule->command('sendMessage:weekly')
        // ->timezone('Asia/Kolkata')
        // ->weeklyOn(0, '20:00');


        // $schedule->command('assign:leads')
        // ->timezone('Asia/Kolkata')
        // ->dailyAt('09:00');
        /*vinay<*/
        $schedule->command('update:weekly_whatsapp_point')
            ->timezone('Asia/Kolkata')
            ->sundays()->at('02:00');

        //$schedule->command('lastfiveday:backup') ->timezone('Asia/Kolkata') ->dailyAt('02:15');

        //Morning Notification
        $schedule->command('morning:notification')
            ->timezone('Asia/Kolkata')
            ->dailyAt('07:55');


        //        Afternoon notification
        $schedule->command('morning:notification')
            ->timezone('Asia/Kolkata')
            ->dailyAt('13:25');


        //Evening notification
        $schedule->command('morning:notification')
            ->timezone('Asia/Kolkata')
            ->dailyAt('20:55');

        /*
        //Daily app Notification
        $schedule->command('daily:appNotification')
        ->dailyAt('19:55')
        ->timezone('Asia/Kolkata')
        ->runInBackground();


        $schedule->command('sendEvening:webnotification')
        ->dailyAt('19:52')
        ->timezone('Asia/Kolkata')
        ->runInBackground();

        $schedule->command('sendAfternoon:webnotification')
        ->dailyAt('13:20')
        ->timezone('Asia/Kolkata')
        ->runInBackground();
*/


        /*$schedule->command('system:command')
        ->everyThirtyMinutes();*/
        /*
        $schedule->command('morning:webPushNotification')
        ->dailyAt('07:55')
        ->timezone('Asia/Kolkata')
        ->runInBackground();
*/
        // $schedule->command('update:onlineScore')
        // ->dailyAt('00:20')
        // ->timezone('Asia/Kolkata')
        // ->runInBackground();

        // $schedule->command('app:NotDAUNotification')
        // ->timezone('Asia/Kolkata')
        // ->dailyAt('13:20')
        // ->runInBackground();

        $schedule->command('update:firstLike')
            ->dailyAt('00:00')
            ->runInBackground();

        $schedule->command('update:zGoodnessScore')
            ->hourly()
            ->runInBackground();

        $schedule->command('update:dailyActivity')
            ->timezone('Asia/Kolkata')
            ->dailyAt('00:10');


        // $schedule->command('countProf:response')
        // ->timezone('Asia/Kolkata')
        // ->dailyAt('17:02');
        /* delete
         $schedule->command('update:one_time_amount_collected')
         // ->timezone('Asia/Kolkata')
         // ->dailyAt('15:45');
 delete */

        //  ->dailyAt('19:21');
        /*vinay>*/

        //    $schedule->command('calculate:LeadComp')
        //    ->timezone('Asia/Kolkata')
        //->everyMinute();
        //   ->dailyAt('17:30');

        // $schedule->command('partner:config')
        // ->hourly()
        // ->runInBackground();

        // $schedule->command('RecordToCompatibilityTab')
        // ->timezone('Asia/Kolkata')
        // ->dailyAt('00:27');


        // $schedule->command('send:notification')
        // ->timezone('Asia/Kolkata')
        // ->dailyAt('20:00');
        /*Points add on Attendance*/
        $schedule->command('update_points:Attendance')
            ->timezone('Asia/Kolkata')
            ->dailyAt('22:00');

        $schedule->command('update:userActivity')
            ->dailyAt('18:20');

        $schedule->command('update:DAU')
            ->timezone('Asia/Kolkata')
            ->dailyAt('23:45');

        $schedule->command('update:response')
            ->timezone('Asia/Kolkata')
            ->dailyAt('00:30');

        // $schedule->command('update:score')
        // ->timezone('Asia/Kolkata')
        // ->dailyAt('02:00');

        $schedule->command('store:operatorLeads')
            ->everyFiveMinutes();

        $schedule->command('reset:leadDetails')
            ->timezone('Asia/Kolkata')
            ->dailyAt('01:00');

        // Re Assign lead to online if followup call date is older then 30 days
        // $schedule->command('Reassign:LeadtoOnline')
        //     ->timezone('Asia/Kolkata')
        //     ->dailyAt('00:01');

        $schedule->command('migrate:userdatas')
            ->timezone('Asia/Kolkata')
            ->dailyAt('22:00');

        $schedule->command('send:virtualLike')->cron('0 */2 * * *'); // every 2 hours
        // $schedule->command('send:audiovirtualLike')->cron('0 */2 * * *'); // every 2 hours

    }
    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

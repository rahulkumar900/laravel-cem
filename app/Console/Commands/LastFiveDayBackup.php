<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;

use Carbon\Carbon;
use App\DbBackup;
use Illuminate\Console\Command;

class LastFiveDayBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lastfiveday:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To save the backup of last 5 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    /**
     * Handle to take backup of the database last 5 days.
     *
     * @SWG\Post(path="/cronLast5DayBackup",
     *   summary="to take backup of database of last 5 days",
     *   description="
            business logic -> for any disaste we do keep backup of last 5 days, every night at 2 am, we export the database and save onto s3
            table used  -> db_backup
            code logic -> keep the count of previous day number backup, save this into db_backup and run the export data command and save in s3, and after saving on s3, increase the count
     ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="login user",
     *     description="JSON Object which login user",
     *     required=true,
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="email", type="string", example="user@mail.com"),
     *         @SWG\Property(property="password", type="string", example="password"),
     *     )
     *   ),
     *   @SWG\Response(response="200", description="Return token or error message")
     * )
     *
     */
    public function handle()
    {
        //
        //mysqldump -u {} -p {} dbname > file.sql
     /*   $db_backup = DbBackup::orderBy('created_at', 'desc')
        ->first();
        // Get the Variables
        $db_current = $db_backup->current_count;

        if($db_current == 0){
        $filename = 'day1';
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $command = "mysqldump -u {$user} -p{$pass} {$database} > {$filename}.sql";

        $process = new Process($command);
        $process->start();

        while($process->isRunning()){
            $s3 = \Storage::disk('s3');
            $s3->put('database_backup/'. $filename .'.sql', file_get_contents("{$filename}.sql"), 'public');
        }
        unlink($filename.'.sql');
        $db_backup->current_count = 1;
        $db_backup->save();
        }
        else if($db_current == 1)
        {
            $filename = 'day2';
            $user = env('DB_USERNAME');
            $pass = env('DB_PASSWORD');
            $database = env('DB_DATABASE');

            $command = "mysqldump -u {$user} -p{$pass} {$database} > {$filename}.sql";

            $process = new Process($command);
            $process->start();

            while($process->isRunning()){
                $s3 = \Storage::disk('s3');
                $s3->put('database_backup/'. $filename .'.sql', file_get_contents("{$filename}.sql"), 'public');
            }
            unlink($filename.'.sql');
            $db_backup->current_count = 2;
            $db_backup->current_file_name;
            $db_backup->save();
        }
        else if($db_current == 2)
        {
            $filename = 'day3';
            $user = env('DB_USERNAME');
            $pass = env('DB_PASSWORD');
            $database = env('DB_DATABASE');

            $command = "mysqldump -u {$user} -p{$pass} {$database} > {$filename}.sql";

            $process = new Process($command);
            $process->start();

            while($process->isRunning()){
                $s3 = \Storage::disk('s3');
                $s3->put('database_backup/'. $filename .'.sql', file_get_contents("{$filename}.sql"), 'public');
            }
            unlink($filename.'.sql');
            $db_backup->current_count = 3;
            $db_backup->current_file_name;
            $db_backup->save();
        }
        else if($db_current == 3)
        {
            $filename = 'day4';
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $database = env('DB_DATABASE');


        $command = "mysqldump -u {$user} -p{$pass} {$database} > {$filename}.sql";

        $process = new Process($command);
        $process->start();

        while($process->isRunning()){
            $s3 = \Storage::disk('s3');
            $s3->put('database_backup/'. $filename .'.sql', file_get_contents("{$filename}.sql"), 'public');
        }
        unlink($filename.'.sql');
        $db_backup->current_count = 4;
        $db_backup->current_file_name;
        $db_backup->save();
        }
        else if($db_current == 4)
        {
            $filename = 'day5';
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $command = "mysqldump -u {$user} -p{$pass} {$database} > {$filename}.sql";

        $process = new Process($command);
        $process->start();

        while($process->isRunning()){
            $s3 = \Storage::disk('s3');
            $s3->put('database_backup/'. $filename .'.sql', file_get_contents("{$filename}.sql"), 'public');
        }
        unlink($filename.'.sql');
        $db_backup->current_count = 0;
        $db_backup->save();
        }*/


    }
}

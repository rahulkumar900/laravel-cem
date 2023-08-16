<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Filesystem;

use Carbon\Carbon;
use Illuminate\Console\Command;

class backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    This is for database backup to the AWS s3
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will take backup of current database';

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
    public function handle()
    {
        //mysqldump -u {} -p {} dbname > file.sql

        // Get the Variables
        $filename = 'Weekly_database_backup';

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
    }
}

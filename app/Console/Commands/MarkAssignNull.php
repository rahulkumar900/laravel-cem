<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;


class MarkAssignNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update leads assign by and assign to according condition and update request by ';

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
     * Handle to save the facebook leads(facebook graph api, an external api) incomplete_leads table .
     *
     * @SWG\Post(path="/cronTostoreFacebookLeads",
     *   summary="store facebook leads in incomplete_leads table",
     *   description="

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


    public function handle(){
                // update leads request by online where it is null
        //UPDATE leads SET request_by = null WHERE request_by = 'online';
        $update = Lead::where('request_by','online')->update(['request_by'=>null]);

        // check assign by and assign to and update
        $total_records = Lead::get();
        foreach ($total_records as $record) {
            if ($record->assign_by==null && $record->assign_to !=null) {
                $update_record = Lead::where('id',$record->id)->update(['assign_by'=>$record->assign_to]);
            }
            else if($record->assign_to==null && $record->assign_by !=null){
                $update_record = Lead::where('id',$record->id)->update(['assign_to'=>$record->assign_by]);
            }
            else if($record->assign_by==null && $record->assign_to==null){
                $update_record = Lead::where('id',$record->id)->update(['assign_to'=>'online','assign_by'=>'online']);
            }
        }
        echo "Done";
    }

    public function updatePreferences(){
     $upadte_table = DB::update("UPDATE compatibilities SET virtual_send= CONCAT('[',virtual_send ,']') WHERE  virtual_send like '%}'");
    }
}


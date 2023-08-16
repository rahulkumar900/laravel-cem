<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lead;
use App\Models\IncompleteLeads;
use App\Models\UserData as Profile;
use App\Models\UserCompatblity as Compatibility;

class resetLeadDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:leadDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Handle to reset the required lead details every night.
     *
     * @SWG\Post(path="/cron/resetLeadDetails",
     *   summary="reset the required lead details every night",
    * description=" business logic=>reset the required lead details every night table used=>leads,incomplete_leads variable used=>call_count ->how many times tele executive has called the leads for the followup to the lead in a day, making it to 0 for the next day. request_by ->which tele executive has requested for the lead, it will have temple_id of the tele executive who has request for the assignment.And making it null, to make sure that no leads gets waste, if a user has requested a lead but do nate call the lead. first_time ->variable to define that user is coming first time on hans platform in a day. discoverCompatiblity ->the compatibility used to show profiles in see more, to refresh it, making it to null so that on a new day, it gets calculated again discover_profile_left ->reseting the count to 20, so the user gets maximum profile ",
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
        Lead::where('id','>','0')
            ->update(['request_by' => null]);

        IncompleteLeads::where('isDelete',0)->update(['request_by' => null]);
        Profile::where('id','>',0)->update(['request_by' => null]);

        Compatibility::where('id','>','0')->update([
            'discovery_profile_left' => 20,
            'first_time' => 0,
            'discoverCompatibility' => null
        ]);
        echo "command run successfully";
    }
}

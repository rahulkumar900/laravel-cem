<?php

namespace App\Console\Commands;

use App\Models\Compatibility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class weeklyWhatsappPoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:weekly_whatsapp_point';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating whatsapp_point each week, update the whatsapp_point/credit to the premium user by 5 points';

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
     * cron job to udpate the credit/whtatsapp_point to the premium user each week.
     *
     * @SWG\Post(path="/updatePremiumCredit",
     *   summary="Updating whatsapp_point each week, update the whatsapp_point/credit to the premium user by 5 points",
     *   description="Updating whatsapp_point each week, update the whatsapp_point/credit to the premium user by 5 points
      table used => compatiblities,profiles,preferences,families
		code logic => fetch all the users who are premium clients and add 5 credits in whatsapp_point column of compatibilities table
      ",
     *   produces={"application/json"},
     *   consumes={"application/json"},
     *     @SWG\Parameter(
     *     in="body",
     *     name="update the credit to the premium client",
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
        $records = DB::table('userPreferences as pre')->join('user_data as pro', function ($join) {
            $join->on('pro.id', '=', 'pre.user_data_id');
            $join->on('pro.temple_id', '=', 'pre.temple_id');
        })
            ->whereRaw("(((pro.is_premium=1) OR (pre.amount_collected >7000) OR (pre.amount_collected = 1) OR (pro.plan_name like '%Personalized%')) and pro.created_at > '2019-02-01 00:00:00')")->select('pro.id as id')->get();
        //dd(sizeof($records));
        $count = 0;
        foreach ($records as $record) {
            echo "Checking Out ID " . $record->id . "----\n";
            Compatibility::where('user_id', $record->id)->update(['whatsapp_point' => 5]);
            $count++;
            echo "Count " . $count;
        }
    }
}

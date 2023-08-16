<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Lead;
use App\Models\User;
use App\Models\UserData;

class DividePendingLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'divide:pendingLeads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Divide pending leads between customer support executive approval ones';

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
        $users_count = User::where('is_approval_cce', 1)->select('temple_id')->get();

        $activeTempleArray = array();
        $allotedTempleArray = array();
        $i = 0;
        $j = 0;
        foreach ($users_count as $list) {
            array_push($activeTempleArray, $list->temple_id);
            $i++;
        }

        // get list of temple that having pending leads
        $check = UserData::whereNotNull('pending_temple_id')->select('pending_temple_id')->groupBy('pending_temple_id')->get();
        foreach ($check as $list) {
            array_push($allotedTempleArray, $list->pending_temple_id);
            $j++;
        }
       // dd(count($check->toArray()));
        // get unactive temple_id for approval
        if ($i > $j) {
            $deallocateTempleArray = array_diff($activeTempleArray, $allotedTempleArray);
        } else {
            $deallocateTempleArray = array_diff($allotedTempleArray, $activeTempleArray);
        }

        // free pending Lead that are allocate unactive temple_id
        if ($deallocateTempleArray) {
            foreach ($deallocateTempleArray as $value) {
                DB::table('user_data')->update([
                    'pending_temple_id' => NULL
                ]);
            }
            echo "Deallocation of leads successful.";
        } else {
            echo "No leads for Deallocation.";
        }

        // get list of user to be alloted for approval
        $userList = Lead::join('user_data', 'leads.user_data_id', '=', 'user_data.id')
            ->whereNotNull('user_data.user_mobile')
            ->where('user_data.pending_temple_id', null)
            ->where('unapprove_carousel', null)
            ->where('user_data.isApproved', '0')
            ->where('user_data.is_deleted', '0')
            //->where('user_data.is_not_interested', '0')
            ->whereRaw('user_data.name not like "%test%"')
            ->where('user_data.name', '!=', 'Hans Lead')
            ->select('user_data.user_mobile as mobile', 'leads.id as id', 'user_data.id as profile_id')
            ->orderBy('user_data.created_at', 'DESC')
            ->get();
        if ($userList) {
            foreach ($userList as $list) {
                $mobile = substr($list->mobile, -2);
                if (is_numeric($mobile)) {
                    $value = $mobile % $i;
                    if ($i == 1) {
                        UserData::where('id', $list->profile_id)->update(
                            [
                                'pending_temple_id' => $activeTempleArray[0],
                                "is_deleted"        => 0,
                                "not_interested"    => 0,
                                "is_approve_ready"  => 0,
                                "is_approved"       => 0
                            ]
                        );
                    } else {
                        UserData::where('id', $list->profile_id)->update(
                            [
                                'pending_temple_id' => $activeTempleArray[$value],
                                "is_deleted"        => 0,
                                "not_interested"    => 0,
                                "is_approve_ready"  => 0,
                                "is_approved"       => 0
                            ]
                        );
                    }
                }
            }
            echo "Allocation of leads successful.";
        } else {
            echo "No New Leads for Allocation.";
        }
    }
}

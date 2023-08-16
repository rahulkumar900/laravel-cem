<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compatibility extends Model
{
    use HasFactory;
    protected $table = "userCompatibilities";
    protected $fillable = [
        'id',
        'userM',
        'userF',
        'assigned',
        'whatsapp_point',
        'amount_collected',
        'profile_status',
        'profile_length',
        'contacted_count',
        'reject_count',
        'shortlist_count',
        'shown_interest_count',
        'pending_count',
        'contact_interest_count',
        'mutual_count',
        'ri_count',
        'is_recalculate',
        'si_count',
        'esi_count',
        'eci_count',
        'eri_count',
        'discovery_profile_left',
        'first_time',
        'discoverCompatibility',
        'ci_status',
        'ri_status',
        'si_status',
        'first_comp_day',
        'max',
        'max_new',
        'virtual_send',
        'virtual_send_count',
        'virtual_receive',
        'virtualToLike',
        'virtual_receive_count',
        'free_id',
        'free_credit_count',
        'free_ids',
        'random_count',
        'random_updated',
        'viewProfile_count',
        'viewed_profiles',
        'negative_contacted_profiles',
        'today_activation_link',
        'free_credits_given',
        'user_data_id'
    ];
}
